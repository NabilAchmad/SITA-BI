<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tugas_akhir')->insert([
            [
                'judul' => 'Sistem Informasi Akademik Berbasis Web',
                'mahasiswa_id' => 1,
                'status' => 'Disetujui',
                'dosen_pembimbing' => 'Dr. Budi Santoso',
            ],
            [
                'judul' => 'Analisis Data Penjualan Menggunakan Data Mining',
                'mahasiswa_id' => 2,
                'status' => 'Dalam Proses',
                'dosen_pembimbing' => 'Dr. Siti Aminah',
            ],
            [
                'judul' => 'Pengembangan Aplikasi Mobile untuk Monitoring Kesehatan',
                'mahasiswa_id' => 3,
                'status' => 'Disetujui',
                'dosen_pembimbing' => 'Dr. Agus Wijaya',
            ],
        ]);
    }
}
