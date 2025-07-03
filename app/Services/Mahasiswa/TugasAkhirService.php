<?php

namespace App\Services\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        // PERBAIKAN DI SINI: Hapus tanda komentar untuk mengaktifkan scope.
        return $this->mahasiswa->tugasAkhir()
            ->active() // <-- BARIS INI SEKARANG AKTIF
            ->with([
                'bimbinganTa' => fn($q) => $q->latest('tanggal_bimbingan'),
                'revisiTa' => fn($q) => $q->latest(),
                'dokumenTa' => fn($q) => $q->latest(),
                'sidang' => fn($q) => $q->latest(),
                'peranDosenTa.dosen.user',
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

    public function getCancelledTugasAkhir()
    {
        // Mengambil semua Tugas Akhir yang sudah dibatalkan
        return $this->mahasiswa->tugasAkhir()
            ->where('status', TugasAkhir::STATUS_DIBATALKAN)
            ->with(['peranDosenTa.dosen.user'])
            ->latest()
            ->get();
    }

    /**
     * Menangani proses unggah proposal, termasuk menghapus file lama,
     * menyimpan file baru, dan memperbarui database.
     *
     * @param TugasAkhir $tugasAkhir Model TugasAkhir yang akan diupdate.
     * @param UploadedFile $file File proposal yang baru diunggah.
     * @return string Path file yang baru disimpan.
     */
    public function handleUploadProposal(TugasAkhir $tugasAkhir, UploadedFile $file): string
    {
        // 1. Hapus file lama jika ada
        if ($tugasAkhir->file_path && Storage::disk('public')->exists($tugasAkhir->file_path)) {
            Storage::disk('public')->delete($tugasAkhir->file_path);
        }

        // 2. Simpan file baru dan dapatkan path-nya
        $filePath = $file->store('proposal_ta', 'public');

        // 3. Update path di database
        $tugasAkhir->update(['file_path' => $filePath]);

        return $filePath;
    }
}
