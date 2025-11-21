<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $userExists = DB::table('users')->where('email', $request->email)->exists();

        if (!$userExists) {
            return back()->withErrors(['email' => 'We can’t find a user with that email address.']);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => 'A password reset link has been sent to your email.']);
        }

        return back()->withErrors(['email' => 'Something went wrong. Please try again later.']);
    }
}
