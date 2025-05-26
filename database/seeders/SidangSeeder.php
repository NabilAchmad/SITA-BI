<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SidangSeeder extends Seeder
{
    public function run()
    {
        // Ambil 30 tugas akhir pertama
        $taList = DB::table('tugas_akhir')->limit(30)->get();

        foreach ($taList as $ta) {
            // Pilih secara acak jenis sidang antara 'proposal' atau 'akhir'
            $jenisSidang = ['proposal', 'akhir'][array_rand(['proposal', 'akhir'])];

            DB::table('sidang')->insert([
                'tugas_akhir_id' => $ta->id,
                'jenis_sidang'   => $jenisSidang,
                'status'         => 'menunggu',
                'is_active'      => true,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);
        }
    }
}
