<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin Dummy',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // tidak dipakai tapi tetap perlu
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
