<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class KajurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Kajur user
        $kajurUser = User::create([
            'name' => 'Kajur User',
            'email' => 'kajur@example.com',
            'password' => Hash::make('password123'), // Change password as needed
        ]);

        // Find Kajur role or create if not exists
        $kajurRole = Role::firstOrCreate(['nama_role' => 'kajur']);

        // Assign Kajur role to user
        UserRole::create([
            'user_id' => $kajurUser->id,
            'role_id' => $kajurRole->id,
        ]);
    }
}
