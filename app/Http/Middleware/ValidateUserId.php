<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-ID');

        // Check if X-User-ID header is present
        if (!$userId) {
            return response()->json(['error' => 'X-User-ID header is required'], 400);
        }

        // Fetch the user based on the user_id and attach it to the request
        $user = User::whereUuid($userId)->first();

        // Perform your validation logic here
        if (!$user) {
            return response()->json(['error' => 'Invalid user ID on headers'], 400);
        }

        // Attach the user to the request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
