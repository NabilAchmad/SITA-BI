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
use Database\Seeders\MahasiswaMenungguSidangAkhirSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            RuanganSeeder::class,
            DummyMahasiswaSeeder::class,
            MahasiswaMenungguSidangAkhirSeeder::class,
        ]);
    }
}
