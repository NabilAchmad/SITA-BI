<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. BUAT SEMUA PERMISSIONS YANG DIPERLUKAN
        // Kelola Akun
        Permission::findOrCreate('manage user accounts', 'web');

        // Pengumuman
        Permission::findOrCreate('view pengumuman', 'web');
        Permission::findOrCreate('manage pengumuman', 'web'); // Izin untuk CRUD

        // Penugasan Bimbingan
        Permission::findOrCreate('full access penugasan pembimbing', 'web'); // Hak akses penuh

        // Fitur Lainnya
        Permission::findOrCreate('manage sidang', 'web');
        Permission::findOrCreate('view laporan', 'web');
        Permission::findOrCreate('view logs', 'web');


        // 2. AMBIL SEMUA ROLE
        $adminRole = Role::findByName('admin');
        $kajurRole = Role::findByName('kajur');
        $kaprodiD3Role = Role::findByName('kaprodi-d3');
        $kaprodiD4Role = Role::findByName('kaprodi-d4');


        // 3. BERIKAN PERMISSIONS KE SETIAP ROLE

        // >> Aturan untuk Admin
        $adminRole->syncPermissions(Permission::all()); // Admin dapat semua izin

        // >> Aturan untuk Kajur
        $kajurRole->syncPermissions([
            'view pengumuman', // Hanya lihat
            'full access penugasan pembimbing', // Akses penuh
            'manage sidang',
            'view laporan',
        ]);

        // >> Aturan untuk Kaprodi (Diberi izin yang sama dengan Kajur)
        $kaprodiD3Role->syncPermissions($kajurRole->permissions);
        $kaprodiD4Role->syncPermissions($kajurRole->permissions);
    }
}
