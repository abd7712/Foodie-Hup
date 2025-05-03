<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    public function create_admin(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "name"=>"required|string|max:255",
            "email"=>"required|string|email|ends_with:gmail.com|unique:users",
            "password"=>"required|string|min:8",
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ]);
        }

        $admin=User::create([
            "name"=>$request->input("name"),
            "email"=>$request->input("email"),
            "password"=>Hash::make($request->input("password")),
            "role"=>"admin",
            "email_verified_at"=>now(),
        ]);

        return response()->json(['message' => 'Admin created successfully']);
    }

    public function create_waiter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|string|email|ends_with:gmail.com|unique:users",
            "password" => "required|string|min:8",
        ]);

        if ($validator->fails()) {
            return response([
                "errors" => $validator->errors()
            ], 422);
        }

        $waiter = User::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
            "role" => "waiter",
            "email_verified_at" => now(),
        ]);

        return response()->json(['message' => 'Waiter created successfully'], 201);
    }

    public function create_chef(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "name"=>"required|string|max:255",
            "email"=>"required|string|email|ends_with:gmail.com|unique:users",
            "password"=>"required|string|min:8",
        ]);

        if($validator->fails())
        {
            return response([
                "errors"=>$validator->errors()
            ]);
        }

        $chef=User::create([
            "name"=>$request->input("name"),
            "email"=>$request->input("email"),
            "password"=>Hash::make($request->input("password")),
            "role"=>"chef",
            "email_verified_at"=>now()
        ]);

        return response()->json(['message' => 'chef created successfully']);
    }

    public function show_admins()
    {
        $admins=User::with("user_status")->where("role","admin")->get();
        if($admins->isEmpty())
        {
            return response([
                "error"=>"no admins found"
            ],404);
        }
        return response([
            "admins"=>$admins
        ],200);
    }

    public function show_chefs()
    {
        $chefs=User::with("user_status")->where("role","chef")->get();

        return response([
            "chefs"=>$chefs
        ],200);
    }   

    public function show_waiters()
    {
        $waiters = User::where("role", "waiter")->get();

        if ($waiters->isEmpty()) {
            return response()->json(["message" => "No waiters found"], 404);
        }

        return response()->json(["waiters" => $waiters], 200);
    }

   
    


}
