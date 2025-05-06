<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->where('email', 'admin@example.com')->first();
        $adminRole = DB::table('roles')->where('nama_role', 'admin')->first();

        if ($user && $adminRole) {
            DB::table('user_roles')->insert([
                [
                    'user_id' => $user->id,
                    'role_id' => $adminRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
