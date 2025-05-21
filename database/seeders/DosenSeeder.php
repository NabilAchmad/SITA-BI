<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6

class DosenSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
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
=======
        $faker = Faker::create();
        for ($i = 1; $i <= 30; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Dosen ' . $i,
                'email' => "dosen$i@example.com",
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 4, // dosen
            ]);

<<<<<<< HEAD
            // Tambahan peran kaprodi dan kajur
=======
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
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
