<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
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

            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', 
            ],
        ], [
            'password.regex' => 'Password must contain letters and numbers only.',
        ]);

        $allowed = DB::table('allowed_emails')->where('email', $request->email)->exists();

        if (!$allowed) {
            DB::table('allowed_emails')->insert([
                'email'      => $request->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        User::create([
            'username'    => $request->username,
            'firstname'   => $request->firstname,
            'lastname'    => $request->lastname,
            'name'        => $request->firstname . ' ' . $request->lastname,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'is_verified' => true,
        ]);

        return redirect()
            ->route('login.view')
            ->with('success', 'Registration successful! You can now log in.');
    }
}
