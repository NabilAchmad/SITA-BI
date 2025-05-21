<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidangSeeder extends Seeder
{
    public function run()
    {
        // Ambil 30 tugas akhir pertama untuk dibuatkan sidang
        $taList = DB::table('tugas_akhir')->get();

        foreach ($taList as $ta) {
            DB::table('sidang')->insert([
                'tugas_akhir_id' => $ta->id,
                'jenis_sidang' => 'akhir',
                'status' => 'dijadwalkan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}