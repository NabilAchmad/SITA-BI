<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache roles dan permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kaprodi-d3']);
        Role::create(['name' => 'kaprodi-d4']);
        Role::create(['name' => 'kajur']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'mahasiswa']);
    }
}
