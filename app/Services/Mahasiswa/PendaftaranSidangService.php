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
        // Ambil mahasiswa yang sedang login
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        // ✅ PERBAIKAN: Mengambil Tugas Akhir yang aktif dan berelasi dengan mahasiswa.
        // Ini lebih aman karena secara eksplisit mencari TA yang relevan dan aktif.
        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->where('status', 'disetujui') // Asumsi ada kolom 'is_active' untuk menandai TA yang berjalan
            ->first();

        // Jika tidak ada tugas akhir yang aktif, lempar exception.
        if (!$tugasAkhir) {
            throw new \Exception("Anda tidak memiliki data tugas akhir yang sedang aktif.");
        }

        // Memulai transaksi untuk memastikan semua data tersimpan atau tidak sama sekali
        return DB::transaction(function () use ($request, $tugasAkhir, $mahasiswa) {

            // Definisikan berkas dan path penyimpanannya
            $filesToStore = [
                'file_naskah_ta'     => $request->file('file_naskah_ta'),
                'file_toeic'         => $request->file('file_toeic'),
                'file_rapor'         => $request->file('file_rapor'),
                'file_ijazah_slta'   => $request->file('file_ijazah_slta'),
                'file_bebas_jurusan' => $request->file('file_bebas_jurusan'),
            ];

            $storedPaths = [];
            foreach ($filesToStore as $key => $file) {
                // Simpan setiap file dan dapatkan path-nya
                $path = $file->store("pendaftaran_sidang/{$tugasAkhir->id}", 'public');
                $storedPaths[$key] = $path;
            }

            // Buat record baru di tabel pendaftaran_sidang
            $pendaftaran = PendaftaranSidang::create([
                'tugas_akhir_id'        => $tugasAkhir->id,
                'status_verifikasi'     => 'menunggu_verifikasi',
                'status_pembimbing_1'   => 'menunggu',
                'status_pembimbing_2'   => 'menunggu',
                'file_naskah_ta'        => $storedPaths['file_naskah_ta'],
                'file_rapor'            => $storedPaths['file_rapor'],
                'file_toeic'            => $storedPaths['file_toeic'],
                'file_ijazah_slta'      => $storedPaths['file_ijazah_slta'],
                'file_bebas_jurusan'    => $storedPaths['file_bebas_jurusan'],
            ]);

            Log::info("Pendaftaran sidang berhasil dibuat untuk mahasiswa ID: {$mahasiswa->id}, Pendaftaran ID: {$pendaftaran->id}");

            return $pendaftaran;
        });
    }
}
