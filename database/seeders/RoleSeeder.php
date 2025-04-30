<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['nama_role' => 'admin', 'deskripsi' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'mahasiswa', 'deskripsi' => 'Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'dosen', 'deskripsi' => 'Dosen', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
