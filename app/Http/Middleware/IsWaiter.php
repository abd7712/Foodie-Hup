<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsWaiter
{
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::guard("user")->user();
        if($user->role!="waiter")
        {
            return response()->json([
                'message' => 'You are not authorized to perform this action. only waiters can be'
            ], 403);
        }
        return $next($request);
    }
}
