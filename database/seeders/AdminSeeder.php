<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Cek role admin
        $adminRole = Role::where('nama_role', 'admin')->first();

        if (!$adminRole) {
            $adminRole = Role::create([
                'nama_role' => 'admin',
                'deskripsi' => 'Administrator sistem'
            ]);
        }

        // Buat user admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // ganti password sesuai kebutuhan
                'photo' => null, // default jika ada
            ]
        );

        // Hubungkan role admin ke user ini jika belum
        if (!$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
