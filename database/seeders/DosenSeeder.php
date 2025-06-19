<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $gelarList = ['S.Pd.', 'M.Pd.', 'M.A.', 'Ph.D.'];

        for ($i = 1; $i <= 30; $i++) {
            // Ambil nama tanpa gelar
            $namaTanpaGelar = $faker->firstName() . ' ' . $faker->lastName();

            // Pilih gelar secara acak
            $gelar = $faker->randomElement($gelarList);

            // Tambahkan gelar di akhir nama
            $namaDosen = $namaTanpaGelar . ', ' . $gelar;

            // Buat email yang natural
            $emailUsername = Str::slug($namaTanpaGelar, '.'); // Contoh: agus.salim
            $email = $emailUsername . $i . '@example.com';

            $userId = DB::table('users')->insertGetId([
                'name' => $namaDosen,
                'email' => $email,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4, // dosen
            ]);

            // Tambahan peran kaprodi dan kajur
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
