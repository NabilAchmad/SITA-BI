<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MahasiswaSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\DosenSeeder;
use Database\Seeders\MahasiswaTanpaPembimbingSeeder;
use Database\Seeders\TugasAkhirSeeder;
use Database\Seeders\SidangSeeder;
use Database\Seeders\RuanganSeeder;

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
            MahasiswaTanpaPembimbingSeeder::class,
            TugasAkhirSeeder::class,
<<<<<<< HEAD
            SidangSeeder::class,
            RuanganSeeder::class,
=======
            PeranDosenTaSeeder::class,
            SidangSeeder::class,
            RuanganSeeder::class,
            JadwalSidangSeeder::class,
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
        ]);
    }
}
