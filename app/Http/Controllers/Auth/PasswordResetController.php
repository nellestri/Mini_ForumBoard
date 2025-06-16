<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // For demo purposes, we'll create a simple token-based reset
        // In production, you'd use Laravel's built-in password reset functionality
        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);

        // Store token in session for demo (in production, use password_resets table)
        session(['password_reset_token' => $token, 'password_reset_email' => $request->email]);

        return back()->with('success', 'Password reset link sent! Use token: ' . $token);
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        return view('auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Verify token (simplified for demo)
        if (session('password_reset_token') !== $request->token ||
            session('password_reset_email') !== $request->email) {
            return back()->withErrors(['token' => 'Invalid or expired token.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        session()->forget(['password_reset_token', 'password_reset_email']);

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now login.');
    }
}
