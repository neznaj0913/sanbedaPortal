<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        $excludedEmails = [
            'ictsnoreply@gmail.com',
            'system.admin@sanbeda-alabang.edu.ph',
        ];

        $emailSearch = $request->input('email', null);

        $users = User::whereNotIn('email', $excludedEmails)
            ->when($emailSearch, function ($query, $emailSearch) {
                return $query->where('email', 'like', '%' . $emailSearch . '%');
            })
            ->orderByDesc('id')
            ->get();

        return view('admin.admin', [
            'users' => $users,
            'searchEmail' => $emailSearch
        ]);
    }


    // Update user
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('super.dashboard')->with('success', 'User updated successfully.');
    }


    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('super.dashboard')->with('success', 'User deleted successfully.');
    }
}
