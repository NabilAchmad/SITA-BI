<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenPeranSeeder extends Seeder
{
    public function run()
    {
        // Ambil user dosen
        $userDosen = DB::table('users')->where('email', 'dosen@example.com')->first();

        // Masukkan ke tabel jabatan_dosen
        DB::table('jabatan_dosen')->insert([
            'user_id' => $userDosen->id,
            'jabatan' => 'ketua_program_studi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ambil dosen_id dari tabel dosen
        $dosen = DB::table('dosen')->where('user_id', $userDosen->id)->first();

        // Buat tugas akhir
        $tugasAkhirId = DB::table('tugas_akhir')->insertGetId([
            'mahasiswa_id' => 1,
            'judul' => 'Sistem Informasi Skripsi',
            'abstrak' => 'Sistem ini bertujuan untuk membantu manajemen skripsi secara digital.',
            'status' => 'diajukan',
            'tanggal_pengajuan' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Peran dosen sebagai pembimbing
        DB::table('peran_dosen_ta')->insert([
            'dosen_id' => $dosen->id,
            'tugas_akhir_id' => $tugasAkhirId,
            'peran' => 'pembimbing1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
