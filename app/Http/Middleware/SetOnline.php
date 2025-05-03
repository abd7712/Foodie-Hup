<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetOnline
{ 
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard("user")->user();
        
        $user->user_status->update([
            "last_activity" => now(),
            "status" => "online"
        ]);
        
        return $next($request);
    }
}
