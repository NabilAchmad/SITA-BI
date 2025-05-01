<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['nama_role' => 'admin', 'deskripsi' => 'Administrator sistem'],
            ['nama_role' => 'dosen', 'deskripsi' => 'Dosen kampus'],
            ['nama_role' => 'mahasiswa', 'deskripsi' => 'Mahasiswa aktif'],
            ['nama_role' => 'tamu', 'deskripsi' => 'Pengguna tidak terdaftar'],
        ]);
    }
}
