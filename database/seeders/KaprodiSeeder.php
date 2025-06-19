<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaprodiSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan Kaprodi untuk D3 Bahasa Inggris
        $userKaprodiD3 = DB::table('users')->insertGetId([
            'name' => 'Maridiati, S.S., M.Bing.',
            'email' => 'kaprodi_d3@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $userKaprodiD3,
            'role_id' => 2, // dosen
        ]);

        DB::table('jabatan_dosen')->insert([
            'user_id' => $userKaprodiD3,
            'jabatan' => 'ketua_program_studi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Menambahkan Kaprodi untuk D4 Bahasa Inggris
        $userKaprodiD4 = DB::table('users')->insertGetId([
            'name' => 'Dr. Marjono, S.S., M.Bing.',
            'email' => 'kaprodi_d4@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $userKaprodiD4,
            'role_id' => 2, // dosen
        ]);

        DB::table('jabatan_dosen')->insert([
            'user_id' => $userKaprodiD4,
            'jabatan' => 'ketua_program_studi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

