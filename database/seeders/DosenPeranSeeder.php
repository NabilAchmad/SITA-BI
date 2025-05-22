<?php

// database/seeders/DosenPeranSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenPeranSeeder extends Seeder
{
    public function run()
    {
        // Ambil user_id dosen dari user dengan email dosen@example.com
        $userDosen = DB::table('users')->where('email', 'dosen@example.com')->first();

        // Jabatan dosen
        DB::table('jabatan_dosen')->insert([
            'user_id' => $userDosen->id,
            'jabatan' => 'ketua_program_studi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
            'mahasiswa_id' => 1, // pastikan mahasiswa ID 1 sudah ada di tabel mahasiswa
            'judul' => 'Sistem Informasi Skripsi',
            'abstrak' => 'Sistem ini bertujuan untuk membantu manajemen skripsi secara digital.',
            'status' => 'diajukan', // salah satu dari enum: diajukan, disetujui, ditolak, selesai
            'tanggal_pengajuan' => now()->toDateString(), // format: YYYY-MM-DD
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peran_dosen_ta')->insert([
            'user_id' => $userDosen->id,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'pembimbing1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
