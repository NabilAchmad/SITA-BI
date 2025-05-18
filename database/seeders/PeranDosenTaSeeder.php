<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeranDosenTaSeeder extends Seeder
{
    public function run()
    {
        $taList = DB::table('tugas_akhir')->get();
        $dosenIds = DB::table('dosen')->pluck('id')->toArray();

        foreach ($taList as $ta) {
            shuffle($dosenIds);
            $selected = array_slice($dosenIds, 0, 4);

            $roles = ['pembimbing1', 'pembimbing2', 'penguji1', 'penguji2'];

            foreach ($roles as $i => $role) {
                DB::table('peran_dosen_ta')->insert([
                    'dosen_id' => $selected[$i],
                    'tugas_akhir_id' => $ta->id,
                    'peran' => $role,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
