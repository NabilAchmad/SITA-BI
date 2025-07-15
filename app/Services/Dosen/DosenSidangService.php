<?php

namespace App\Services\Dosen;

use App\Models\PendaftaranSidang;
use App\Models\Sidang;
use App\Models\TugasAkhir;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

/**
 * Service ini mengelola semua logika bisnis terkait verifikasi sidang dari sisi Dosen.
 */
class DosenSidangService
{
    /**
     * Memproses keputusan dosen (menyetujui/menolak) untuk pendaftaran sidang.
     *
     * @param PendaftaranSidang $pendaftaran
     * @param Dosen $dosen
     * @param string $status 'disetujui' or 'ditolak'
     * @param string|null $catatan
     * @return PendaftaranSidang
     * @throws \Exception|UnauthorizedException
     */
    public function prosesKeputusanDosen(PendaftaranSidang $pendaftaran, Dosen $dosen, string $status, ?string $catatan): PendaftaranSidang
    {
        $tugasAkhir = $pendaftaran->tugasAkhir;

        // 1. Otorisasi: Pastikan dosen yang login adalah pembimbing yang sah
        $kolomStatus = null;
        $kolomCatatan = null;

        if ($tugasAkhir->pembimbing_1_id == $dosen->id) {
            $kolomStatus = 'status_pembimbing_1';
            $kolomCatatan = 'catatan_pembimbing_1';
        } elseif ($tugasAkhir->pembimbing_2_id == $dosen->id) {
            $kolomStatus = 'status_pembimbing_2';
            $kolomCatatan = 'catatan_pembimbing_2';
        } else {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
        
        // 2. Gunakan transaksi untuk menjaga konsistensi data
        DB::transaction(function () use ($pendaftaran, $kolomStatus, $kolomCatatan, $status, $catatan) {
            
            // 3. Update status dan catatan dari dosen yang bersangkutan
            $pendaftaran->update([
                $kolomStatus => $status,
                $kolomCatatan => $catatan,
            ]);

            // 4. Logika penentuan status akhir pendaftaran
            if ($status === 'ditolak') {
                // Jika salah satu dosen menolak, status akhir langsung 'berkas_tidak_lengkap'
                $pendaftaran->status_verifikasi = 'berkas_tidak_lengkap';
                $pendaftaran->save();
                // Keluar dari transaksi karena proses selesai
                return;
            }

            // Jika dosen ini menyetujui, cek status dosen lainnya
            if ($pendaftaran->status_pembimbing_1 === 'disetujui' && $pendaftaran->status_pembimbing_2 === 'disetujui') {
                // Jika KEDUANYA sudah setuju, update status akhir dan buat jadwal sidang
                $pendaftaran->status_verifikasi = 'disetujui';
                $pendaftaran->save();

                // Buat record baru di tabel 'sidang' dengan status 'dijadwalkan'
                // Cek dulu agar tidak duplikat
                Sidang::firstOrCreate(
                    ['pendaftaran_sidang_id' => $pendaftaran->id], // Kunci untuk mencari
                    [ // Data untuk dibuat jika tidak ditemukan
                        'tugas_akhir_id' => $pendaftaran->tugas_akhir_id,
                        'status_hasil' => 'dijadwalkan',
                    ]
                );
            }
        });

        // Kembalikan model pendaftaran yang sudah di-refresh dari database
        return $pendaftaran->fresh();
    }
}
