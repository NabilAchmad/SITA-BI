<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Jangan lupa untuk import Str

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin SITA-BI',
            'email' => 'admin@sita-bi.test',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(), // Menambahkan email_verified_at dengan timestamp saat ini
            'remember_token' => Str::random(60), // Membuat remember token acak
        ]);
    }
}
