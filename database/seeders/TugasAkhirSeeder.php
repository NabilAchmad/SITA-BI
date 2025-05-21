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
            DB::table('tugas_akhir')->insert([
                'mahasiswa_id' => $mhs->id,
                'judul' => 'Analisis Sistem Informasi Mahasiswa ' . $mhs->id,
                'abstrak' => 'Penelitian tentang sistem informasi untuk ' . $mhs->nim,
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 20)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
