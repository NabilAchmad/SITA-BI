<?php

namespace App\Services\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TugasAkhirService
{
    protected Mahasiswa $mahasiswa;

    /**
     * Service ini selalu bekerja dalam konteks mahasiswa yang sedang login.
     */
    public function __construct()
    {
        $this->mahasiswa = Auth::user()->mahasiswa;
    }

    /**
     * Mengambil data Tugas Akhir aktif dengan semua relasi
     * yang dibutuhkan untuk halaman progress mahasiswa.
     *
     * @return TugasAkhir|null
     */
    public function getActiveTugasAkhirForProgressPage(): ?TugasAkhir
    {
        return $this->mahasiswa->tugasAkhir()
            ->active() // <- Menggunakan scope dari model
            ->with([
                'bimbinganTa' => fn($q) => $q->latest('tanggal_bimbingan'),
                'revisiTa' => fn($q) => $q->latest(),
                'dokumenTa' => fn($q) => $q->latest(),
                'sidang' => fn($q) => $q->latest(),
                // Eager load relasi spesifik yang sudah kita buat
                'pembimbingSatu.dosen.user',
                'pembimbingDua.dosen.user',
            ])
            ->latest()
            ->first();
    }

    /**
     * Membuat pengajuan tugas akhir baru berdasarkan data yang sudah divalidasi.
     *
     * @param array $data
     * @return TugasAkhir
     * @throws \Exception
     */
    public function createTugasAkhir(array $data): TugasAkhir
    {
        if ($this->mahasiswa->tugasAkhir()->active()->exists()) {
            // Melempar exception agar bisa ditangkap oleh controller
            throw new \Exception('Anda masih memiliki tugas akhir yang sedang aktif.');
        }

        return $this->mahasiswa->tugasAkhir()->create([
            'judul' => $data['judul'],
            'abstrak' => $data['abstrak'],
            'status' => TugasAkhir::STATUS_DIAJUKAN,
            'tanggal_pengajuan' => now(),
        ]);
    }

    /**
     * Mengajukan proses pembatalan untuk tugas akhir.
     *
     * @param TugasAkhir $tugasAkhir
     * @param string|null $alasan
     * @return bool
     * @throws \Exception
     */
    public function requestCancellation(TugasAkhir $tugasAkhir, ?string $alasan): bool
    {
        // Otorisasi: Pastikan TA ini milik mahasiswa yang sedang login
        if ($tugasAkhir->mahasiswa_id !== $this->mahasiswa->id) {
            throw new \Exception('Anda tidak berhak mengakses tugas akhir ini.');
        }

        // Validasi state: Pastikan TA bisa dibatalkan
        if (!in_array($tugasAkhir->status, [TugasAkhir::STATUS_DIAJUKAN, TugasAkhir::STATUS_DISETUJUI, TugasAkhir::STATUS_REVISI])) {
            throw new \Exception('Tugas akhir dengan status saat ini tidak dapat dibatalkan.');
        }

        $tugasAkhir->status = TugasAkhir::STATUS_MENUNGGU_PEMBATALAN;
        $tugasAkhir->alasan_pembatalan = $alasan;

        // Reset persetujuan pembimbing
        $tugasAkhir->peranDosenTa()->whereIn('peran', ['pembimbing1', 'pembimbing2'])->update([
            'setuju_pembatalan' => null,
            'tanggal_verifikasi' => null,
            'catatan_verifikasi' => null,
        ]);

        return $tugasAkhir->save();
    }
}
