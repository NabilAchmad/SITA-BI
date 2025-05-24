<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MahasiswaTanpaPembimbingSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 101; $i <= 130; $i++) {
            $namaMahasiswa = $faker->firstName() . ' ' . $faker->lastName();

            $userId = DB::table('users')->insertGetId([
                'name' => $namaMahasiswa,
                'email' => "mhs_tanpa_pembimbing$i@example.com",
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
                'prodi' => 'D4 Bahasa Inggris',
                'angkatan' => '25',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
