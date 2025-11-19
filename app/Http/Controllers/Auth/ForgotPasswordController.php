<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password page.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // ✅ Validate that the email exists in your users table
        $request->validate([
            'email' => 'required|email',
        ]);

        // ✅ Check if user exists manually before proceeding
        $userExists = DB::table('users')->where('email', $request->email)->exists();

        if (!$userExists) {
            return back()->withErrors(['email' => 'We can’t find a user with that email address.']);
        }

        // ✅ Use Laravel’s built-in broker to send reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // ✅ Show feedback messages
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => 'A password reset link has been sent to your email.']);
        }

        return back()->withErrors(['email' => 'Something went wrong. Please try again later.']);
    }
}
