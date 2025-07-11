<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Sidang;

class MahasiswaMenungguSidangAkhirSeeder extends Seeder
{
    public function run()
    {
        $jumlahMahasiswa = 10;

        for ($i = 1; $i <= $jumlahMahasiswa; $i++) {
            // Generate unique NIM and email
            $nimPrefix = '231108'; // Example prefix for D4 program
            $nim = $nimPrefix . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $namaDepan = 'Mahasiswa';
            $namaBelakang = Str::random(5);
            $namaLengkap = "$namaDepan $namaBelakang";
            $email = Str::slug($namaLengkap, '') . "@student.ac.id";

            // Create user
            $user = User::create([
                'name' => $namaLengkap,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            // Assign mahasiswa role using Spatie's assignRole
            $user->assignRole('mahasiswa');

            // Create mahasiswa record
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'prodi' => 'd4',
                'angkatan' => 2023,
                'kelas' => 'a',
            ]);

            // Create tugas akhir with status 'disetujui'
            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => 'Judul Tugas Akhir Menunggu Sidang Akhir',
                'status' => 'disetujui',
                'tanggal_pengajuan' => now()->subDays(rand(10, 60)),
            ]);

            // Create sidang with jenis_sidang 'akhir' and status 'menunggu'
            Sidang::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'jenis_sidang' => 'akhir',
                'status' => 'menunggu',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
