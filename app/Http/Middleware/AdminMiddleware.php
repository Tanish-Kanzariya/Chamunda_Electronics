<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
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
        // If unauthenticated, auto-authenticate admin in local development so owner isn't locked out
        if (!Auth::check()) {
            if (app()->environment('local')) {
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    Auth::login($admin);
                    return $next($request);
                }
            }
            return redirect()->route('login')->with('error', 'Access denied. Administrator privileges required.');
        }

        // If logged in but not an admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
