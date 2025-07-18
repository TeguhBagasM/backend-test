<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah login',
            ], 400);
        }

        return $next($request);
    }
}