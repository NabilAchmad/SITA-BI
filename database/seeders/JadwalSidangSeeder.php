<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSidangSeeder extends Seeder
{
    public function run()
    {
        $sidangList = DB::table('sidang')->get();
        $ruanganIds = DB::table('ruangan')->pluck('id')->toArray();

        foreach ($sidangList as $index => $sidang) {
            DB::table('jadwal_sidang')->insert([
                'sidang_id' => $sidang->id,
                'tanggal' => now()->addDays($index % 7)->format('Y-m-d'),
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '10:30:00',
                'ruangan_id' => $ruanganIds[array_rand($ruanganIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
