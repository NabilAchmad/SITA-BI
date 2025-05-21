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

        for ($i = 1; $i <= 30; $i++) {
<<<<<<< HEAD
            // Nama tanpa gelar
            $namaMahasiswa = $faker->firstName() . ' ' . $faker->lastName();

            $userId = DB::table('users')->insertGetId([
                'name' => $namaMahasiswa,
                'email' => "mhs_tanpa_pembimbing" . ($i + 100) . "@example.com",
=======
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => "mhs_tanpa_pembimbing" . ($i + 100) . "@example.com", // agar unik & aman
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 5, // mahasiswa
            ]);

            $mhsId = DB::table('mahasiswa')->insertGetId([
                'user_id' => $userId,
<<<<<<< HEAD
                'nim' => '25' . str_pad($i + 100, 7, '0', STR_PAD_LEFT),
=======
                'nim' => '25' . str_pad($i + 100, 7, '0', STR_PAD_LEFT), // mulai dari 251000001
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'prodi' => 'D4 Bahasa Inggris',
                'angkatan' => '25',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhsId,
                'judul' => 'Analisis Tugas Akhir Tanpa Pembimbing ' . $i,
<<<<<<< HEAD
                'abstrak' => $faker->paragraph(),
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 90))->toDateString(),
=======
                'abstrak' => $faker->paragraph,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 90)),
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
