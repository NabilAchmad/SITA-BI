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

        // Ambil ID dosen dari tabel dosen
        $dosen = DB::table('dosen')->where('user_id', $dosenPembimbingId)->first();

        $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
            'mahasiswa_id' => 1,
            'judul' => 'Sistem Informasi Akademik',
            'abstrak' => 'Aplikasi akademik berbasis web untuk kampus.',
            'status' => 'disetujui',
            'tanggal_pengajuan' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peran_dosen_ta')->insert([
            'dosen_id' => $dosen->id,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'pembimbing1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peran_dosen_ta')->insert([
            'dosen_id' => $dosen->id,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'penguji1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
