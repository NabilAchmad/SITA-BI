<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
<<<<<<< HEAD
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
=======
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ruangan')->insert([
                'nama_ruangan' => 'Ruang ' . $i,
                'lokasi' => 'Gedung B Lantai ' . rand(1, 3),
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
                'kapasitas' => rand(15, 30),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
