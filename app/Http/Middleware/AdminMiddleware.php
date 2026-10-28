<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Administrator authentication required.');
        }

        // Strictly verify admin role
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Access Denied: You do not have administrator permissions.');
        }

        return $next($request);
    }
}
