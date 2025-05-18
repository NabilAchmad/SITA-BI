<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ruangan')->insert([
                'nama_ruangan' => 'Ruang ' . $i,
                'lokasi' => 'Gedung B Lantai ' . rand(1, 3),
                'kapasitas' => rand(15, 30),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
