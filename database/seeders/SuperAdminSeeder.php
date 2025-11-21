<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'firstname'   => 'Super',
            'lastname'    => 'Admin',
            'name'        => 'Super Admin',
            'username'    => 'superadmin',
            'email'       => 'ictsnoreply@gmail.com',
            'password'    => Hash::make('ictsnewn0rmal'),
            'super_admin' => 'SAFE-ADMIN-KEY-1',
        ]);
    }
}
