<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidangSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        // Ambil 30 tugas akhir pertama untuk dibuatkan sidang
        $taList = DB::table('tugas_akhir')->get();
=======
        // Ambil 30 tugas akhir pertama
        $taList = DB::table('tugas_akhir')->limit(30)->get();
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a

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
<<<<<<< HEAD
}
=======
}
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
