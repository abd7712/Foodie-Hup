<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdminOrAdmin
{
   
    public function handle(Request $request, Closure $next): Response
    {
        $u=Auth::guard("user")->user();

        if($u->role==="user" || $u->role==="chef")
        {
            return response()->json([
                'message' => 'You are not authorized to perform this action. only super admins and admins can be'
            ], 403);
        }
        
        return $next($request);
    }
}
