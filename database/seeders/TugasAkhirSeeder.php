<?php

namespace Database\Seeders;

use App\Models\TugasAkhir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TugasAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        TugasAkhir::insert([
            [
                'mahasiswa_id' => 1,
                'judul' => 'Analisis Sistem Informasi Akademik Berbasis Web',
                'abstrak' => 'Penelitian ini membahas pengembangan sistem informasi akademik berbasis web untuk meningkatkan efisiensi pengelolaan data mahasiswa.',
                'status' => 'diajukan',
                'alasan_penolakan' => null,
                'tanggal_pengajuan' => '2025-06-01',
                'file_path' => 'files/tugas_akhir/ta1.pdf',
            ],
            [
                'mahasiswa_id' => 1,
                'judul' => 'Implementasi Algoritma Machine Learning untuk Prediksi Kelulusan',
                'abstrak' => 'Studi ini mengimplementasikan algoritma machine learning untuk memprediksi kelulusan mahasiswa berdasarkan data akademik.',
                'status' => 'disetujui',
                'alasan_penolakan' => null,
                'tanggal_pengajuan' => '2025-05-15',
                'file_path' => 'files/tugas_akhir/ta2.pdf',
            ],
            [
                'mahasiswa_id' => 1,
                'judul' => 'Pengembangan Aplikasi Mobile untuk Monitoring Kesehatan',
                'abstrak' => 'Aplikasi mobile ini dikembangkan untuk memonitor kesehatan pengguna secara real-time menggunakan sensor wearable.',
                'status' => 'ditolak',
                'alasan_penolakan' => 'Judul tidak sesuai dengan bidang studi',
                'tanggal_pengajuan' => '2025-04-20',
                'file_path' => 'files/tugas_akhir/ta3.pdf',
            ],
        ]);
    }
}
