<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "name"=>"required|string|max:255",
            "email"=>"required|string|email|ends_with:gmail.com",
            "password"=>"required|string|min:8",
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ]);
        }

        $user=User::create([
            "name"=>$request->input("name"),
            "email"=>$request->input("email"),
            "password"=>Hash::make($request->input("password"))
        ]);

        $verificationUrl=url("/api/email/verify/{$user->id}/".sha1($user->email));

        Mail::to($user->email)->send(new WelcomeMail($user->name,$verificationUrl));

        return response([
            "message" => "User registered successfully. Please check your email to verify your account."
        ], 201);
    }

    public function verify($user_id,$hash)
    {
        $user=User::find($user_id);

        if(!$user)
        {
            return response()->view("account-deleted");
        }

        if(!hash_equals(sha1($user->email),$hash))
        {
            return response()->view("invalid-verification-link");
        }

        if(!$user->hasVerifiedEmail())
        {
            $user->markEmailAsVerified();
            return response()->view("emailVerify");
        }

        if($user->hasVerifiedEmail())
        {
            return response()->view("verified");
        }
    }

    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "email"=>"required|string",
            "password"=>"required|string"
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ],422);
        }

        $cacheKey="LoginAttempts".$request->userAgent();
        $attempts=Cache::get($cacheKey,0);

        if($attempts>3)
        {
            return response([
                "message" => "Whoa! You’ve tried to log in too many times. Take a short break and try again in a 30 seconds."
            ], 429);            
        }

        $user=User::where("email",$request->input("email"))->first();

        if($user && Hash::check($request->input("password"),$user->password))
        {
            if(!$user->email_verified_at)
            {
                return response([
                    "message"=>"We’re still waiting for you to verify your email. Please check your inbox (or spam folder) to activate your account!"
                ],422);
            }

            $e=$user->user_status()->exists();

            if($e)
            {
                if($user->user_status->account_status==="banned")
                {
                    return response()->json([
                        'message' => 'Oops! Your account has been banned. Reach out to our support team if you need help.'
                    ], 403);                    
                }

                if($user->user_status->account_status==="inactive")
                {
                    $user->user_status->update([
                        "account_status"=>"active",
                        "last_activity"=>now(),
                        "last_login"=>now(),
                        "status"=>"online"
                    ]);
                }
                
                if($user->user_status->account_status==="active")
                {
                    $user->user_status->update([
                        "last_activity"=>now(),
                        "last_login"=>now(),
                        "status"=>"online"
                    ]);
                }                
              
            }

            else{
                $user->user_status()->create([
                   "account_status"=>"active",
                   "last_activity"=>now(),
                    "last_login"=>now(),
                    "status"=>"online"
                ]);
            }
              
            if($request->input("remember_me"))
            {
                JWTAuth::factory()->setTTL(60*24*30);
            }
            else{
                JWTAuth::factory()->setTTL(60*24*15);
            }

            $token=JWTAuth::fromUser($user);
            
            Cache::forget($cacheKey);

            return response([
                "message"=>"login successfully",
                "role"=>$user->role,
                "token"=>$token
            ],200);
        }

        Cache::put($cacheKey,++$attempts,30);

        return response([
            "error" => "Wrong email or password. Please try again."
        ],400);

    }

    public function logout()
    {
        $token=JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return response([
            "message"=>"logout successfully"
        ]);
    }

        

}
