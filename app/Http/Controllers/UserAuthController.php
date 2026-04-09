<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.',
                ], 422);
            }

            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'is_admin' => true,
                    'redirect' => route('admin.dashboard'),
                ]);
            }

            return redirect()->route('admin.dashboard');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_admin' => false,
                'openAccountSidebar' => true,
            ]);
        }

        return back()->with('openAccountSidebar', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}