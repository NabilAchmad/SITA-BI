<?php

namespace App\Services\Admin;

use App\Models\PeranDosenTa; // ✅ PENTING: Menggunakan model PeranDosenTa sebagai sumber data utama.
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PenugasanService
{
    /**
     * Mengambil daftar mahasiswa yang SUDAH memiliki pembimbing.
     * Logika ini sekarang sesuai dengan model TugasAkhir Anda.
     */
    public function getMahasiswaWithPembimbing(Request $request): LengthAwarePaginator
    {
        // ✅ SESUAI MODEL: Menggunakan whereHas('peranDosenTa') untuk memeriksa
        // apakah ada entri di tabel `peran_dosen_ta` yang berelasi.
        // Ini adalah cara yang benar sesuai dengan relasi `peranDosenTa()` di model Anda.
        return TugasAkhir::with(['mahasiswa.user', 'dosenPembimbing.user']) // Muat semua relasi yang dibutuhkan untuk view
            ->whereHas('dosenPembimbing')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Mengambil daftar mahasiswa yang BELUM memiliki pembimbing.
     * Logika ini juga sudah disesuaikan dengan model TugasAkhir Anda.
     */
    public function getMahasiswaNeedingPembimbing(Request $request): LengthAwarePaginator
    {
        // Query ini sekarang akan mengambil Tugas Akhir yang:
        // 1. Statusnya 'disetujui' (sesuai konstanta di model TugasAkhir).
        // 2. Belum memiliki entri sama sekali di tabel `peran_dosen_ta`.
        return TugasAkhir::with('mahasiswa.user')
            ->where('status', TugasAkhir::STATUS_DISETUJUI) // <-- Filter status ditambahkan di sini
            ->whereDoesntHave('dosenPembimbing')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * ✅ LOGIKA INTI YANG SUDAH DISESUAIKAN:
     * Menetapkan atau memperbarui pembimbing dengan memanipulasi tabel `peran_dosen_ta`.
     * Ini adalah metode yang benar berdasarkan struktur model Anda.
     *
     * @param TugasAkhir $tugasAkhir Objek Tugas Akhir yang akan diatur.
     * @param array $validatedData Data dari Form Request, berisi 'pembimbing_1_id' dan 'pembimbing_2_id'.
     */
    public function assignOrUpdatePembimbing(TugasAkhir $tugasAkhir, array $validatedData): void
    {
        DB::transaction(function () use ($tugasAkhir, $validatedData) {

            // --- Proses Pembimbing 1 ---
            // Menggunakan updateOrCreate pada model PeranDosenTa.
            // Metode ini akan:
            // 1. MENCARI baris dengan tugas_akhir_id dan peran 'pembimbing1'.
            // 2. JIKA DITEMUKAN, akan meng-UPDATE dosen_id nya.
            // 3. JIKA TIDAK DITEMUKAN, akan MEMBUAT baris baru.
            PeranDosenTa::updateOrCreate(
                [
                    'tugas_akhir_id' => $tugasAkhir->id,
                    'peran'          => PeranDosenTa::PERAN_PEMBIMBING_1, // Menggunakan konstanta dari model
                ],
                [
                    'dosen_id'       => $validatedData['pembimbing_1_id'],
                ]
            );

            // --- Proses Pembimbing 2 ---
            // Pastikan pembimbing_2_id ada dan tidak kosong sebelum diproses
            if (!empty($validatedData['pembimbing_2_id'])) {
                PeranDosenTa::updateOrCreate(
                    [
                        'tugas_akhir_id' => $tugasAkhir->id,
                        'peran'          => PeranDosenTa::PERAN_PEMBIMBING_2, // Menggunakan konstanta dari model
                    ],
                    [
                        'dosen_id'       => $validatedData['pembimbing_2_id'],
                    ]
                );
            }
        });
    }
}
