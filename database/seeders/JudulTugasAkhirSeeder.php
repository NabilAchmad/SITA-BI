<?php

// database/seeders/JudulTugasAkhirSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JudulTugasAkhir;
use Illuminate\Support\Facades\File;

class JudulTugasAkhirSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat
        JudulTugasAkhir::truncate();

        $path = database_path('data'); // Path ke folder data
        $files = File::glob("{$path}/*.csv");

        foreach ($files as $file) {
            // Ekstrak tahun dari nama file
            preg_match('/-(\d{4})/', $file, $matches);
            $tahun = $matches[1] ?? null;

            if (($handle = fopen($file, 'r')) !== FALSE) {
                // Lewati 4 baris header
                for ($i = 0; $i < 4; $i++) {
                    fgetcsv($handle);
                }

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    // Sesuaikan indeks kolom dengan struktur CSV Anda
                    // [1] => NIM, [2] => NAMA MAHASISWA, [3] => JUDUL TUGAS AKHIR
                    if (isset($data[1]) && isset($data[2]) && isset($data[3])) {
                        JudulTugasAkhir::create([
                            'nim' => $data[1],
                            'nama_mahasiswa' => $data[2],
                            'judul' => $data[3],
                            'tahun_lulus' => $tahun,
                        ]);
                    }
                }
                fclose($handle);
            }
        }
    }
}
