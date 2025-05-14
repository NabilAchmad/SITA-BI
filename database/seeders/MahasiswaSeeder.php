<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;  // Import Faker

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Membuat instance Faker
        $faker = Faker::create('id_ID'); // Menggunakan locale id_ID untuk nama Indonesia

        for ($i = 1; $i <= 50; $i++) {
            // Tentukan prodi dan NIM secara acak untuk mahasiswa
            $prodi = $i % 2 == 0 ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris';
            $nim = ($prodi == 'D3 Bahasa Inggris' ? '23' : '24') . str_pad($i, 7, '0', STR_PAD_LEFT);

            $userId = DB::table('users')->insertGetId([
                'name' => $faker->firstName . ' ' . $faker->lastName,  // Menggunakan nama depan dan belakang tanpa gelar
                'email' => 'mahasiswa' . $i . '@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 3, // mahasiswa
            ]);

            DB::table('mahasiswa')->insert([
                'user_id' => $userId,
                'nim' => $nim,
                'prodi' => $prodi,
                'angkatan' => substr($nim, 0, 2), // Ambil 2 digit pertama NIM untuk angkatan
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
