<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Gunakan lokal Indonesia

        for ($i = 1; $i <= 30; $i++) {
            $namaDosen = $faker->name;

            $userId = DB::table('users')->insertGetId([
                'name' => $namaDosen,
                'email' => "dosen$i@example.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4, // dosen
            ]);

            // Beri peran tambahan untuk 3 dosen pertama
            if ($i <= 2) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 2, // kaprodi
                ]);
            } elseif ($i == 3) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 3, // kajur
                ]);
            }

            DB::table('dosen')->insert([
                'user_id' => $userId,
                'nidn' => 'NIDN' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
