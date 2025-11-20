<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // points to resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
     $request->validate([
    'email' => 'required|email',
    'password' => 'required',
]);


        $credentials = $request->only('email', 'password');

       if (Auth::attempt($credentials, $request->filled('remember'))) {
    $user = Auth::user();
    $request->session()->regenerate();

    // ✅ Check if superadmin
    if ($user->migrationr === 'SAFE-ADMIN-KEY-1') {
        return redirect('/console'); // superadmin hidden panel
    }

    // Normal users
    return redirect()->route('dashboard');
}

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.view');
    }
}
