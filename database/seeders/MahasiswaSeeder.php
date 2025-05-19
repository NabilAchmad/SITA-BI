<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $name = $faker->name();
            $email = $faker->unique()->safeEmail();

            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 5, // mahasiswa
            ]);

            DB::table('mahasiswa')->insert([
                'user_id' => $userId,
                'nim' => '25' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'prodi' => $i % 2 === 0 ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris',
                'angkatan' => '25',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
