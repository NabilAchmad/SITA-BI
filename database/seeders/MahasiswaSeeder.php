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

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

<<<<<<< HEAD
        // Ambil dulu semua dosen yg sudah ada (id dan nama)
        $dosenIds = DB::table('dosen')->pluck('id')->toArray();
        $totalDosen = count($dosenIds);

        for ($i = 1; $i <= 50; $i++) {
            // Nama mahasiswa tanpa gelar
            $name = $faker->firstName() . ' ' . $faker->lastName();

            // Email natural berdasarkan nama
            $emailUsername = Str::slug($name, '.');
            $email = $emailUsername . $i . '@example.com';

            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
=======
        for ($i = 1; $i <= 50; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Mahasiswa ' . $i,
                'email' => "mahasiswa$i@example.com",
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 5, // mahasiswa
            ]);

<<<<<<< HEAD
            $mahasiswaId = DB::table('mahasiswa')->insertGetId([
=======
            DB::table('mahasiswa')->insert([
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'user_id' => $userId,
                'nim' => '25' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'prodi' => $i % 2 === 0 ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris',
                'angkatan' => '25',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
<<<<<<< HEAD

            // Insert tugas akhir untuk mahasiswa ini
            $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
                'mahasiswa_id' => $mahasiswaId,
                'judul' => 'Judul Tugas Akhir Mahasiswa ' . $i,
                'abstrak' => $faker->paragraph(),
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 60))->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $jumlahPembimbing = 2;
            shuffle($dosenIds); // acak dosen

            for ($j = 0; $j < $jumlahPembimbing; $j++) {
                DB::table('peran_dosen_ta')->insert([
                    'tugas_akhir_id' => $tugasAkhirId,
                    'dosen_id' => $dosenIds[$j],
                    'peran' => $j === 0 ? 'pembimbing1' : 'pembimbing2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
=======
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
        }
    }
}
