<?php

namespace App\Services\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\PendaftaranSidang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Mahasiswa\PendaftaranSidangRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendaftaranSidangService
{
    /**
     * Menangani logika pendaftaran sidang akhir.
     *
     * @param PendaftaranSidangRequest $request
     * @return PendaftaranSidang
     * @throws \Exception
     */
    public function handleRegistration(PendaftaranSidangRequest $request): PendaftaranSidang
    {
        // 1. Ambil data mahasiswa dan tugas akhir yang aktif.
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        $tugasAkhir = $mahasiswa->tugasAkhir()->where('status', 'disetujui')->first();

        if (!$tugasAkhir) {
            throw new \Exception("Anda tidak memiliki data tugas akhir yang disetujui dan aktif untuk didaftarkan sidang.");
        }

        // 2. Gunakan transaksi database untuk menjaga integritas data.
        return DB::transaction(function () use ($request, $tugasAkhir, $mahasiswa) {

            // 3. Buat record pendaftaran sidang terlebih dahulu (tanpa file).
            $pendaftaran = PendaftaranSidang::create([
                'tugas_akhir_id'        => $tugasAkhir->id,
                'status_verifikasi'     => 'menunggu_verifikasi',
                'status_pembimbing_1'   => 'menunggu',
                'status_pembimbing_2'   => 'menunggu',
            ]);

            // 4. Definisikan file-file yang akan diunggah.
            $filesToUpload = [
                'naskah_ta'     => $request->file('file_naskah_ta'),
                'toeic'         => $request->file('file_toeic'),
                'rapor'         => $request->file('file_rapor'),
                'ijazah_slta'   => $request->file('file_ijazah_slta'),
                'bebas_jurusan' => $request->file('file_bebas_jurusan'),
            ];

            // 5. Loop, simpan setiap file, dan buat record di tabel 'file_uploads'.
            foreach ($filesToUpload as $type => $file) {
                if ($file) {
                    $originalName = $file->getClientOriginalName();
                    $filePath = $file->store("pendaftaran_sidang/{$pendaftaran->id}", 'public');

                    // Buat record FileUpload yang terhubung ke $pendaftaran
                    // menggunakan relasi polimorfik 'files()'.
                    $pendaftaran->files()->create([
                        'file_path'     => $filePath,
                        'original_name' => $originalName,
                        'file_type'     => $type, // Menyimpan tipe file, mis: 'naskah_ta'
                    ]);
                }
            }

            Log::info("Pendaftaran sidang #{$pendaftaran->id} berhasil dibuat untuk mahasiswa #{$mahasiswa->id}.");

            return $pendaftaran;
        });
    }
}
