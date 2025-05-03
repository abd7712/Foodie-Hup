<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserOwnsBooking
{
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::guard("user")->user();
        $book_id=$request->route("book_id");

        $book=$user->books()->find($book_id);

        if (!$book) {
            return response()->json([
                "error" => "Booking not found or does not belong to you."
            ], 404);
        }
    
        if ($book->status !== "arrive") {
            return response()->json([
                "error" => "You can only access this feature after arriving."
            ], 403);
        }
        return $next($request);
    }
}
