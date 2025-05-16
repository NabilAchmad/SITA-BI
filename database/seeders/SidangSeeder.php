<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidangSeeder extends Seeder
{
    public function run()
    {
        // Ambil 30 tugas akhir pertama untuk dibuatkan sidang
        $taList = DB::table('tugas_akhir')->limit(30)->get();

        foreach ($taList as $ta) {
            DB::table('sidang')->insert([
                'tugas_akhir_id' => $ta->id,
                // Pilih salah satu jenis sidang dari enum: 'proposal', 'hasil', 'akhir'
                'jenis_sidang' => 'akhir',  
                // Pilih status yang valid dari enum: 'dijadwalkan', 'selesai', 'ditunda'
                'status' => 'dijadwalkan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
