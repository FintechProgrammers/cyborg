<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson() && !$request->is('api/*')) {

            if (in_array('auth:admin', $request->route()->middleware())) {
                return route('admin.login');
            }

            return route('login');
        }

        return response()->json([
            'success' => false,
            'message' => 'Please check your login credentials.',
            'data' => []
        ], Response::HTTP_UNAUTHORIZED);
    }
}
