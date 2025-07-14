<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache roles dan permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            'manage sidang',
            'manage user accounts',
            'view laporan',
            'manage pengumuman',
            'manage tugas akhir',
            'manage bimbingan',
            'manage penilaian',
<<<<<<< HEAD
            // Add other permissions as needed
=======
            'pantau-semua-bimbingan', // <-- DITAMBAHKAN
            'full access penugasan pembimbing', // <-- TAMBAHKAN PERMISSION BARU
>>>>>>> 2586b3657da907332fa67cf7713a46e78eb33a16
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles
        $roles = [
            'admin' => ['manage sidang', 'manage user accounts', 'view laporan', 'manage pengumuman', 'manage tugas akhir', 'manage bimbingan', 'manage penilaian'],
            'kaprodi-d3' => ['manage sidang', 'view laporan', 'manage tugas akhir', 'manage bimbingan', 'manage penilaian'],
            'kaprodi-d4' => ['manage sidang', 'view laporan', 'manage tugas akhir', 'manage bimbingan', 'manage penilaian'],
            'kajur' => ['manage sidang', 'view laporan', 'manage tugas akhir', 'manage bimbingan', 'manage penilaian'],
            'dosen' => ['manage tugas akhir', 'manage bimbingan', 'manage penilaian'],
            'mahasiswa' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
