<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenPembimbingPengujiSeeder extends Seeder
{
    public function run()
    {
        // Misalnya, dosen dengan peran pembimbing dan penguji
        $dosenPembimbingId = DB::table('users')->insertGetId([
            'name' => 'Dr. Imam Bonjol, S.Si., M.Kom.',
            'email' => 'dosen_pembimbing_penguji@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $dosenPembimbingId,
            'role_id' => 2, // dosen
        ]);

        DB::table('dosen')->insert([
            'user_id' => $dosenPembimbingId,
            'nidn' => '12345789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Misalnya, menambahkan tugas akhir
        $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
            'mahasiswa_id' => 1,
            'judul' => 'Sistem Informasi Akademik',
            'abstrak' => 'Aplikasi akademik berbasis web untuk kampus.',
            'status' => 'disetujui',
            'tanggal_pengajuan' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Menambahkan peran pembimbing dan penguji
        DB::table('peran_dosen_ta')->insert([
            'user_id' => $dosenPembimbingId,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'pembimbing1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peran_dosen_ta')->insert([
            'user_id' => $dosenPembimbingId,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'penguji1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
