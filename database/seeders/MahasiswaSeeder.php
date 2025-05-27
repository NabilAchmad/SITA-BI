<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // === ADMIN ===
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1, // Admin
        ]);

        // === DOSEN (30 dosen termasuk kaprodi dan kajur) ===
        $gelarList = ['S.Pd.', 'M.Pd.', 'M.A.', 'Ph.D.'];
        $dosenIds = [];

        for ($i = 1; $i <= 30; $i++) {
            $namaTanpaGelar = $faker->firstName . ' ' . $faker->lastName;
            $gelar = $faker->randomElement($gelarList);
            $namaDosen = $namaTanpaGelar . ', ' . $gelar;
            $email = Str::slug($namaTanpaGelar, '.') . $i . '@example.com';

            $userId = DB::table('users')->insertGetId([
                'name' => $namaDosen,
                'email' => $email,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                ['user_id' => $userId, 'role_id' => 4], // dosen
            ]);

            if ($i <= 2) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 2, // kaprodi
                ]);
            } elseif ($i === 3) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => 3, // kajur
                ]);
            }

            $dosenId = DB::table('dosen')->insertGetId([
                'user_id' => $userId,
                'nidn' => 'NIDN' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $dosenIds[] = $dosenId;
        }

        // === MAHASISWA DAN TUGAS AKHIR ===
        $statusOptions = ['diajukan', 'disetujui', 'ditolak'];
        for ($i = 1; $i <= 10; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Mahasiswa ' . $i,
                'email' => 'mahasiswa' . $i . '@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 5, // mahasiswa
            ]);

            $prodi = $faker->randomElement(['d3', 'd4']);
            $kelas = $prodi === 'd3'
                ? $faker->randomElement(['a', 'b', 'c'])
                : $faker->randomElement(['a', 'b']);

            $mahasiswaId = DB::table('mahasiswa')->insertGetId([
                'user_id' => $userId,
                'nim' => '21' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'prodi' => $prodi,
                'angkatan' => '2021',
                'kelas' => $kelas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $status = $statusOptions[array_rand($statusOptions)];

            $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
                'mahasiswa_id' => $mahasiswaId,
                'judul' => 'Judul TA Mahasiswa ' . $i,
                'abstrak' => 'Abstrak dari tugas akhir mahasiswa ' . $i,
                'status' => $status,
                'tanggal_pengajuan' => now()->subDays(rand(1, 60)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($status === 'disetujui') {
                shuffle($dosenIds);
                DB::table('peran_dosen_ta')->insert([
                    [
                        'dosen_id' => $dosenIds[0],
                        'tugas_akhir_id' => $tugasAkhirId,
                        'peran' => 'pembimbing1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'dosen_id' => $dosenIds[1],
                        'tugas_akhir_id' => $tugasAkhirId,
                        'peran' => 'pembimbing2',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }

            if ($status === 'disetujui' && $i % 2 === 0) {
                DB::table('sidang')->insert([
                    'tugas_akhir_id' => $tugasAkhirId,
                    'jenis_sidang' => 'akhir',
                    'status' => 'dijadwalkan',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
