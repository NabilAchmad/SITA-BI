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
            $judul = 'Analysis of English Language Learning System for Student ' . $mhs->nim;
            $abstrak = $faker->paragraph(3) . ' This research focuses on improving the English language learning process for students with NIM ' . $mhs->nim . '.';

            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhs->id,
                'judul' => $judul,
                'abstrak' => $abstrak,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 20)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
