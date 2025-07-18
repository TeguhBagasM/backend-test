<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already authenticated',
            ], 403);
        }

        return $next($request);
    }
}