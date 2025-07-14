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
        // ✅ Reset cache permission & role
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ 1. Buat semua permissions yang dibutuhkan
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

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        // ✅ 2. Ambil semua roles
        $adminRole     = Role::findByName('admin');
        $kajurRole     = Role::findByName('kajur');
        $kaprodiD3Role = Role::findByName('kaprodi-d3');
        $kaprodiD4Role = Role::findByName('kaprodi-d4');

        // ✅ 3. Tetapkan permission per role

        // Admin dapat semua permission
        $adminRole->syncPermissions(Permission::all());

        // Kajur
        $kajurRole->syncPermissions([
            'view pengumuman',
            'manage pengumuman',
            'full access penugasan pembimbing',
            'manage sidang',
            'view laporan',
            'manage tugas akhir',
            'manage bimbingan',
            'manage penilaian',
            'pantau-semua-bimbingan',
        ]);

        // Kaprodi D3 & D4: permission sama seperti Kajur
        $kaprodiD3Role->syncPermissions($kajurRole->permissions);
        $kaprodiD4Role->syncPermissions($kajurRole->permissions);
    }
}
