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
            'manage user accounts',
            'view pengumuman',
            'manage pengumuman',
            'full access penugasan pembimbing',
            'manage sidang',
            'view laporan',
            'view logs',
            'manage tugas akhir',
            'manage bimbingan',
            'manage penilaian',
            'pantau-semua-bimbingan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles dan berikan permissions
        $roles = [
            'admin' => [
                'manage sidang',
                'manage user accounts',
                'view laporan',
                'manage pengumuman',
                'manage tugas akhir',
                'manage bimbingan',
                'manage penilaian',
                'full access penugasan pembimbing'
            ],
            'kaprodi-d3' => [
                'manage sidang',
                'view laporan',
                'manage tugas akhir',
                'manage bimbingan',
                'manage penilaian',
                'pantau-semua-bimbingan', // <-- DITAMBAHKAN
                'full access penugasan pembimbing'
            ],
            'kaprodi-d4' => [
                'manage sidang',
                'view laporan',
                'manage tugas akhir',
                'manage bimbingan',
                'manage penilaian',
                'pantau-semua-bimbingan', // <-- DITAMBAHKAN
                'full access penugasan pembimbing'
            ],
            'kajur' => [
                'manage sidang',
                'view laporan',
                'manage tugas akhir',
                'manage bimbingan',
                'manage penilaian',
                'pantau-semua-bimbingan', // <-- DITAMBAHKAN
                'full access penugasan pembimbing'
            ],
            'dosen' => [
                'manage tugas akhir',
                'manage bimbingan',
                'manage penilaian'
            ],
            'mahasiswa' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
