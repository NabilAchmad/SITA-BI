<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TugasAkhirSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $mahasiswaList = DB::table('mahasiswa')->get();

        foreach ($mahasiswaList as $mhs) {
<<<<<<< HEAD
            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhs->id,
                'judul' => 'Analisis Sistem Informasi Mahasiswa ' . $mhs->id,
                'abstrak' => 'Penelitian tentang sistem informasi untuk ' . $mhs->nim,
=======
            $judul = 'Analysis of English Language Learning System for Student ' . $mhs->nim;
            $abstrak = $faker->paragraph(3) . ' This research focuses on improving the English language learning process for students with NIM ' . $mhs->nim . '.';

            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhs->id,
                'judul' => $judul,
                'abstrak' => $abstrak,
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 20)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
