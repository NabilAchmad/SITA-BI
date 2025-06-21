<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role dengan id = 5 adalah mahasiswa
        $mahasiswaRole = Role::firstOrCreate(
            ['id' => 5],
            ['name' => 'mahasiswa']
        );

        // Buat akun user mahasiswa
        $user = User::create([
            'name' => 'Erland Agsya Agustian',
            'email' => 'erlandagsya458@gmail.com',
            'password' => Hash::make('password'), // Ganti jika perlu
        ]);

        // Relasi user-role
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $mahasiswaRole->id,
        ]);

        // Masukkan data ke tabel mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '2311082007', // Harus unik
            'prodi' => 'd4',     // Sesuai enum
            'angkatan' => '2023',
            'kelas' => 'a',      // Sesuai enum
        ]);
        $faker = Faker::create('id_ID');

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
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 5, // mahasiswa
            ]);

            $mahasiswaId = DB::table('mahasiswa')->insertGetId([
                'user_id' => $userId,
                'nim' => '25' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'prodi' => $i % 2 === 0 ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris',
                'angkatan' => '25',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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
        }
    }
}
