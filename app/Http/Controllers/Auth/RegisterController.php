<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AllowedEmail;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // ✅ Points to your Blade file: resources/views/auth/register.blade.php
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ Validate inputs
        $request->validate([
            'username'   => 'required|string|unique:users,username',
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'email'      => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@sanbeda-alabang.edu.ph')) {
                        $fail('Only @sanbeda-alabang.edu.ph emails are allowed.');
                    }
                },
            ],
            'password'   => 'required|min:6|confirmed',
        ]);

        // ✅ Check if email exists in allowed_emails
        $allowed = DB::table('allowed_emails')->where('email', $request->email)->exists();

        if (!$allowed) {
            // 🚀 Add new email to allowed_emails if not found
            DB::table('allowed_emails')->insert([
                'email'      => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ Create new user in the `users` table
        $user = User::create([
            'username'    => $request->username,
            'firstname'   => $request->firstname,
            'lastname'    => $request->lastname,
            'name'        => $request->firstname . ' ' . $request->lastname,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'is_verified' => true,
        ]);

        // ✅ Redirect back to login with success message
        return redirect()->route('login.view')->with('success', 'Registration successful! You can now log in.');
    }
}
