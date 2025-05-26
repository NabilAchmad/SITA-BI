<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // User Admin
        $user = DB::table('users')->where('email', 'admin@example.com')->first();

        if (!$user) {
            $adminId = DB::table('users')->insertGetId([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $adminId = $user->id;
        }


        DB::table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => 1, // admin
        ]);

        // Dosen
        $dosenId = DB::table('users')->insertGetId([
            'name' => 'Dr. Sankoro. S.Si., M.Kom.',
            'email' => 'dosen@example.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $dosenId,
            'role_id' => 2, // dosen
        ]);

        DB::table('dosen')->insert([
            'user_id' => $dosenId,
            'nidn' => '12345678',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mahasiswa
        $mahasiswaId = DB::table('users')->insertGetId([
            'name' => 'Ibarahim',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $mahasiswaId,
            'role_id' => 3, // mahasiswa
        ]);

        DB::table('mahasiswa')->insert([
            'user_id' => $mahasiswaId,
            'nim' => '2212345678',
            'prodi' => 'D4 Bahasa Inggris',
            'angkatan' => '22',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
