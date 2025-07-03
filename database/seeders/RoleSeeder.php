<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Import Schema

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Nonaktifkan pengecekan foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel roles
        DB::table('roles')->truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // 4. Masukkan data role yang baru
        DB::table('roles')->insert([
            ['id' => 1, 'nama_role' => 'admin', 'deskripsi' => 'Administrator sistem'],
            ['id' => 2, 'nama_role' => 'kaprodi-d3', 'deskripsi' => 'Ketua Program Studi D3 Bahasa Inggris'],
            ['id' => 3, 'nama_role' => 'kaprodi-d4', 'deskripsi' => 'Ketua Program Studi D4 Bahasa Inggris'],
            ['id' => 4, 'nama_role' => 'kajur', 'deskripsi' => 'Ketua Jurusan'],
            ['id' => 5, 'nama_role' => 'dosen', 'deskripsi' => 'Dosen kampus'],
            ['id' => 6, 'nama_role' => 'mahasiswa', 'deskripsi' => 'Mahasiswa aktif'],
            ['id' => 7, 'nama_role' => 'tamu', 'deskripsi' => 'Pengguna tidak terdaftar'],
        ]);
    }
}
