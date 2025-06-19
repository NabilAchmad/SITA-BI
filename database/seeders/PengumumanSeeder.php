<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengumuman')->insert([
            [
                'judul' => 'Pendaftaran Sidang TA',
                'isi' => 'Mahasiswa wajib mendaftar sidang TA sebelum 15 Mei 2025 melalui portal akademik.',
                'dibuat_oleh' => 1, // pastikan user ID 1 ada di tabel users
                'audiens' => 'mahasiswa',
                'tanggal_dibuat' => Carbon::create(2025, 4, 25),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Seminar Proposal',
                'isi' => 'Seminar proposal wajib diikuti oleh seluruh mahasiswa tingkat akhir sebelum UAS.',
                'dibuat_oleh' => 1,
                'audiens' => 'mahasiswa',
                'tanggal_dibuat' => Carbon::create(2025, 4, 26),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Libur Nasional',
                'isi' => 'Kampus akan libur pada tanggal 1 Mei 2025 dalam rangka Hari Buruh Internasional.',
                'dibuat_oleh' => 1,
                'audiens' => 'all_users',
                'tanggal_dibuat' => Carbon::create(2025, 4, 24),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Workshop Penulisan Skripsi',
                'isi' => 'Diadakan workshop penulisan skripsi pada 30 April 2025 di Aula B lantai 2.',
                'dibuat_oleh' => 1,
                'audiens' => 'mahasiswa',
                'tanggal_dibuat' => Carbon::create(2025, 4, 23),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Pendaftaran Wisuda',
                'isi' => 'Mahasiswa yang telah lulus wajib melakukan pendaftaran wisuda paling lambat 10 Juni 2025.',
                'dibuat_oleh' => 1,
                'audiens' => 'mahasiswa',
                'tanggal_dibuat' => Carbon::create(2025, 4, 22),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
