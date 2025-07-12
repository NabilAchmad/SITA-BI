<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Sidang;

class DummyMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $jumlahMahasiswa = 20;
        $prodiPrefix = [
            'D3' => '231018',
            'D4' => '231108',
        ];

        // Beberapa contoh nama natural
        $namaList = [
            'Ahmad',
            'Budi',
            'Citra',
            'Dewi',
            'Eka',
            'Fajar',
            'Gita',
            'Hadi',
            'Intan',
            'Joko',
            'Kurnia',
            'Lestari',
            'Mahesa',
            'Nanda',
            'Putri',
            'Qomar',
            'Rizki',
            'Siti',
            'Teguh',
            'Utami'
        ];

        // Beberapa contoh judul TA natural
        $judulList = [
            'Pengembangan Sistem Informasi Akademik Berbasis Web',
            'Rancang Bangun Aplikasi Manajemen Keuangan Pribadi',
            'Implementasi Algoritma Machine Learning untuk Prediksi Cuaca',
            'Sistem Pendukung Keputusan Pemilihan Pegawai Terbaik',
            'Aplikasi Mobile Pencatatan Inventaris Sekolah',
            'Optimasi Jaringan Komputer Menggunakan Metode XYZ',
            'Pengembangan E-Commerce UMKM Berbasis Laravel',
            'Sistem Monitoring Absensi Mahasiswa Berbasis RFID',
            'Penerapan Blockchain untuk Keamanan Data Medis',
            'Aplikasi Manajemen Proyek Konstruksi Berbasis Web'
        ];

        for ($i = 1; $i <= $jumlahMahasiswa; $i++) {
            // Random Prodi
            $prodi = array_rand($prodiPrefix);
            do {
                $nim = $prodiPrefix[$prodi] . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Mahasiswa::where('nim', $nim)->exists());

            // Random nama dan email
            $namaDepan = $namaList[array_rand($namaList)];
            $namaBelakang = Str::random(5);
            $namaLengkap = "$namaDepan $namaBelakang";
            $email = Str::slug($namaLengkap, '') . "@student.ac.id";

            // Insert ke tabel users
            $user = User::create([
                'name' => $namaLengkap,
                'email' => $email,
                'password' => Hash::make('password'), // default password
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Assign role mahasiswa menggunakan Spatie
            $user->assignRole('mahasiswa');

            // Insert ke tabel mahasiswa
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'prodi' => $prodi,
                'angkatan' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert tugas akhir (langsung disetujui)
            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $judulList[array_rand($judulList)],
                // 'abstrak' => 'Abstrak tugas akhir ini dibuat secara otomatis untuk dummy data.',
                'status' => 'disetujui',
                'tanggal_pengajuan' => now()->subDays(rand(5, 60)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert sidang (random proposal/akhir), status menunggu
            $jenisSidang = (rand(0, 1) === 1) ? 'proposal' : 'akhir';

            Sidang::create([
                'tugas_akhir_id' => $ta->id,
                'jenis_sidang' => $jenisSidang,
                'status' => 'menunggu',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
