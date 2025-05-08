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
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DosenPeranSeeder::class,
            DosenPembimbingPengujiSeeder::class,
            MahasiswaSeeder::class,
            KaprodiSeeder::class,
        ]);
    }
}
