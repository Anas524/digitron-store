<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in -> go home (navbar dropdown login)
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login as admin.');
        }

        // Logged in but not admin -> logout and go home
        if (!Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->route('home')->with('error', 'Not allowed.');
        }

        return $next($request);
    }
}
