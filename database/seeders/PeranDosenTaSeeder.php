<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeranDosenTaSeeder extends Seeder
{
    public function run()
    {
        $tugasAkhirList = DB::table('tugas_akhir')->get();
        $dosenIds = DB::table('dosen')->pluck('id')->toArray();

        foreach ($tugasAkhirList as $ta) {
            $selected = collect($dosenIds)->random(2);

            DB::table('peran_dosen_ta')->insert([
                [
                    'dosen_id' => $selected[0],
                    'tugas_akhir_id' => $ta->id,
                    'peran' => 'pembimbing1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'dosen_id' => $selected[1],
                    'tugas_akhir_id' => $ta->id,
                    'peran' => 'pembimbing2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
