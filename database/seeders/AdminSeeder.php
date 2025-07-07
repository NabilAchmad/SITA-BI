<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role; // 1. Gunakan model Role dari Spatie
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 2. Buat atau cari Role 'admin'
        // findOrCreate akan mencari role dengan nama 'admin'. Jika tidak ada, ia akan membuatnya.
        $adminRole = Role::findOrCreate('admin', 'web');

        // 3. Buat atau update user admin
        // updateOrCreate akan mencari user dengan email tersebut, jika ada di-update, jika tidak ada dibuat.
        $adminUser = User::updateOrCreate(
            [
                'email' => 'admin@example.com' // Kunci untuk mencari user
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
            ]
        );

        // 4. Berikan role 'admin' ke user tersebut
        // assignRole adalah metode dari Spatie untuk memberikan role.
        // Metode ini cerdas, ia tidak akan memberikan role yang sama dua kali.
        $adminUser->assignRole($adminRole);
    }
}
