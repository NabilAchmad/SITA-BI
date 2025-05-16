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
        $faker = Faker::create('id_ID');

        // 5 dosen biasa
        for ($i = 1; $i <= 5; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Dosen ' . $faker->lastName,
                'email' => "dosen$i@example.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4, // dosen
            ]);

            DB::table('dosen')->insert([
                'user_id' => $userId,
                'nidn' => '00' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 1 kajur
        $kajurUserId = DB::table('users')->insertGetId([
            'name' => 'Kajur Siti Nurhaliza',
            'email' => 'kajur@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            ['user_id' => $kajurUserId, 'role_id' => 4], // dosen
            ['user_id' => $kajurUserId, 'role_id' => 3], // kajur
        ]);

        DB::table('dosen')->insert([
            'user_id' => $kajurUserId,
            'nidn' => '0011122333',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2 kaprodi
        for ($j = 1; $j <= 2; $j++) {
            $kaprodiUserId = DB::table('users')->insertGetId([
                'name' => 'Kaprodi ' . $faker->lastName,
                'email' => "kaprodi$j@example.com",
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                ['user_id' => $kaprodiUserId, 'role_id' => 4], // dosen
                ['user_id' => $kaprodiUserId, 'role_id' => 2], // kaprodi
            ]);

            DB::table('dosen')->insert([
                'user_id' => $kaprodiUserId,
                'nidn' => '0099776655' . $j,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
