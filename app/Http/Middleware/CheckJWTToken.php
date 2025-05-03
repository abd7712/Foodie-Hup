<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJWTToken
{
   
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user=JWTAuth::parseToken()->authenticate();

            if(!$user)
            {
                return response()->json([
                    "error" => "User not found. Please log in again."
                ], 401);
            }

        }
        catch(TokenExpiredException $e)
        {
            return response()->json([
                "error" => "Your token has expired. Please log in again."
            ], 401);
        }
        catch(TokenInvalidException $e)
        {
            return response()->json([
                "error" => "Invalid token. Please log in again."
            ], 401);
        }
        catch(JWTException $e)
        {
            return response()->json([
                "error" => "Token not provided. Please include a valid token in your request."
            ], 401);
        }
        
        return $next($request);
    }
}
