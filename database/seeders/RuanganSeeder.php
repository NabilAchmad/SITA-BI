<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    protected function generateGedungList(): array
    {
        $gedungList = [];

        // Huruf A-Z
        foreach (range('A', 'Z') as $char) {
            $gedungList[] = $char;
        }

        // Kombinasi dua huruf AA, AB, AC, ... AZ
        foreach (range('A', 'Z') as $firstChar) {
            foreach (range('A', 'Z') as $secondChar) {
                $gedungList[] = $firstChar . $secondChar;
            }
        }

        return $gedungList;
    }

    public function run(): void
    {
        $gedungList = $this->generateGedungList();

        for ($i = 1; $i <= 10; $i++) {
            $gedung = $gedungList[array_rand($gedungList)];
            DB::table('ruangan')->insert([
                'nama_ruangan' => 'Ruang ' . $i,
                'lokasi' => 'Gedung ' . $gedung . ' Lantai ' . rand(1, 3),
                'kapasitas' => rand(15, 30),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
