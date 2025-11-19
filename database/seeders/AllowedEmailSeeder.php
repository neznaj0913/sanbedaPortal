<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AllowedEmail;

class AllowedEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            'jemartinez@sanbeda-alabang.edu.ph',
            'another@sanbeda-alabang.edu.ph',
            'sample@sanbeda-alabang.edu.ph',
        ];

        foreach ($emails as $email) {
            AllowedEmail::firstOrCreate(['email' => strtolower($email)]);
        }
    }
}