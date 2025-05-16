<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSidangSeeder extends Seeder
{
    public function run()
    {
        $sidangList = DB::table('sidang')->get();

        // Ambil semua ruangan yang ada
        $ruanganIds = DB::table('ruangan')->pluck('id')->toArray();

        // Jika tidak ada ruangan, buat satu dummy ruangan
        if (empty($ruanganIds)) {
            $ruanganIds[] = DB::table('ruangan')->insertGetId([
                'nama_ruangan' => 'Ruangan Sidang 1',
                'lokasi' => 'Gedung A',
                'kapasitas' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert jadwal sidang
        foreach ($sidangList as $index => $sidang) {
            DB::table('jadwal_sidang')->insert([
                'sidang_id' => $sidang->id,
                'tanggal' => now()->addDays($index % 10)->format('Y-m-d'),
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '10:30:00',
                'ruangan_id' => $ruanganIds[array_rand($ruanganIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
