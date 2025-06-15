<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        // Check if user has admin role
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            // Log the attempt for debugging
            Log::warning('Non-admin user attempted to access admin area', [
                'user_id' => $user ? $user->id : null,
                'user_role' => $user ? $user->role : null,
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);

            return redirect()->route('forum.index')->with('error', 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
