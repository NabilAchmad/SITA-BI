<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Mahasiswa;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role dengan id = 5 adalah mahasiswa
        $mahasiswaRole = Role::firstOrCreate(
            ['id' => 5],
            ['name' => 'mahasiswa']
        );

        // Buat akun user mahasiswa
        $user = User::create([
            'name' => 'Erland Agsya Agustian',
            'email' => 'erlandagsya458@gmail.com',
            'password' => Hash::make('password'), // Ganti jika perlu
        ]);

        // Assign role mahasiswa menggunakan Spatie
        $user->assignRole('mahasiswa');

        // Masukkan data ke tabel mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '2311082007', // Harus unik
            'prodi' => 'd4',     // Sesuai enum
            'angkatan' => '2023',
            'kelas' => 'a',      // Sesuai enum
        ]);
    }
}
