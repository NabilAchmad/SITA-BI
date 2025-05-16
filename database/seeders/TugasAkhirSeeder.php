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
                'judul' => 'Analisis ' . $faker->word . ' pada Sistem Bahasa',
                'abstrak' => $faker->paragraph(3),
                'status' => 'diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(5, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
