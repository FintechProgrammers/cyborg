<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowWithdrwal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (strtolower(systemSettings()->withdrawal_status) == "disable") {
            return response()->json(['success' => false, 'message' => 'Sorry, you are not permitted to execute this action.'], 403);
        }

        return $next($request);
    }
}
