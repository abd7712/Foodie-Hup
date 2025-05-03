<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::guard("user")->user();
        if($user->role!="user")
        {
            return response()->json([
                'message' => 'You are not authorized to perform this action. only users can be'
            ], 403);
        }
        return $next($request);
    }
}
