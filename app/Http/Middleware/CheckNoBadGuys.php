<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckNoBadGuys
{
   
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::guard("user")->user();

        if($user->user_status->account_status==="banned")
        {
            return response()->json([
                "message" => "Access denied. Your account has been banned. Please contact support if you believe this is a mistake."
            ], 403);
        }

        return $next($request);
    }
}
