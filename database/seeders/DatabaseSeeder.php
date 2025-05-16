<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            TugasAkhirSeeder::class,
            PeranDosenTaSeeder::class,
            SidangSeeder::class,
            JadwalSidangSeeder::class,
            MahasiswaTanpaPembimbingSeeder::class,
        ]);
    }
}
