<?php

namespace App\Services\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\User; // ✅ PERBAIKAN: Import model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\DokumenTa;
use Illuminate\Support\Facades\DB; // <-- 1. Pastikan DB di-import

class TugasAkhirService
{
    protected ?Mahasiswa $mahasiswa;

    /**
     * Service ini selalu bekerja dalam konteks mahasiswa yang sedang login.
     */
    public function __construct()
    {
        // ✅ PERBAIKAN: Membuat konstruktor lebih aman
        $this->mahasiswa = Auth::user()?->mahasiswa;

        if (!$this->mahasiswa) {
            throw new \Exception('User yang login tidak memiliki data mahasiswa yang valid.');
        }
    }

    /**
     * Membuat data Tugas Akhir baru jika belum ada yang aktif.
     *
     * @param array $validatedData Data judul dari request.
     * @return TugasAkhir
     * @throws \Exception
     */
    public function createTugasAkhir(array $validatedData): TugasAkhir
    {
        if ($this->mahasiswa->tugasAkhir()->active()->exists()) {
            throw new \Exception('Anda sudah memiliki Tugas Akhir yang aktif.');
        }

        return $this->mahasiswa->tugasAkhir()->create([
            'judul' => $validatedData['judul'],
            'status' => TugasAkhir::STATUS_DIAJUKAN,
            'tanggal_pengajuan' => now(),
        ]);
    }

    /**
     * @param TugasAkhir $tugasAkhir Instance model TugasAkhir.
     * @param UploadedFile $file File yang di-upload dari request.
     * @param string $tipeDokumen Tipe dokumen.
     * @param string|null $catatan Catatan opsional dari mahasiswa.
     * @return DokumenTa Instance DokumenTa yang baru dibuat.
     */
    /**
     * [PERBAIKAN TOTAL] Menangani upload file dan membuat JEJAK di log
     * menggunakan ID Dokumen, bukan nama file.
     */
    public function handleUploadFile(TugasAkhir $tugasAkhir, UploadedFile $file, string $tipeDokumen, ?string $catatan = null): DokumenTa
    {
        return DB::transaction(function () use ($tugasAkhir, $file, $tipeDokumen, $catatan) {

            // --- Langkah 1: Simpan File & Dokumen (Tidak berubah) ---
            $filePath = $file->store("dokumen_ta/{$tugasAkhir->id}", 'public');
            $tugasAkhir->update(['file_path' => $filePath]);

            $dokumen = $tugasAkhir->dokumenTa()->create([
                'tipe_dokumen' => $tipeDokumen,
                'file_path'    => $filePath,
            ]);

            // --- Langkah 2: Buat Jejak di Log ---
            $sesiBimbingan = $tugasAkhir->bimbinganTa()->where('status_bimbingan', '!=', 'selesai')->latest()->first();
            if (!$sesiBimbingan) {
                $sesiBimbingan = $tugasAkhir->bimbinganTa()->create([
                    'dosen_id' => $tugasAkhir->pembimbingSatu->dosen_id,
                    'peran' => 'pembimbing1',
                    'tanggal_bimbingan' => now(),
                    'status_bimbingan' => 'diajukan', // Gunakan status yang ada di ENUM
                    'sesi_ke' => 1
                ]);
            }

            // ✅ PERBAIKAN UTAMA: Ambil ID dari relasi mahasiswa, bukan user
            $mahasiswaId = Auth::user()->mahasiswa->id;

            // Buat catatan "UPLOAD" otomatis
            $sesiBimbingan->catatan()->create([
                'catatan'     => "UPLOAD_ID:" . $dokumen->id,
                'author_type' => \App\Models\Mahasiswa::class,
                'author_id'   => $mahasiswaId, // Gunakan ID mahasiswa yang benar
            ]);

            if (!empty($catatan)) {
                $sesiBimbingan->catatan()->create([
                    'catatan'     => $catatan,
                    'author_type' => \App\Models\Mahasiswa::class,
                    'author_id'   => $mahasiswaId, // Gunakan ID mahasiswa yang benar
                ]);
            }

            return $dokumen;
        });
    }

    /**
     * Mengajukan pembatalan Tugas Akhir.
     *
     * @param TugasAkhir $tugasAkhir
     * @param string|null $alasan
     * @return void
     * @throws \Exception
     */
    public function requestCancellation(TugasAkhir $tugasAkhir, ?string $alasan): void
    {
        if ($tugasAkhir->mahasiswa_id !== $this->mahasiswa->id) {
            throw new \Exception('Anda tidak berhak mengakses tugas akhir ini.');
        }

        $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_MENUNGGU_PEMBATALAN,
            'alasan_pembatalan' => $alasan,
        ]);
    }

    /**
     * Mengambil data untuk halaman dashboard mahasiswa.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        $tugasAkhir = $this->getActiveTugasAkhir();
        return [
            'tugasAkhir' => $tugasAkhir,
            'sudahMengajukan' => (bool)$tugasAkhir,
        ];
    }

    /**
     * Mengambil data untuk halaman progress tugas akhir mahasiswa.
     *
     * @return array
     */
    public function getProgressPageData(): array
    {
        $tugasAkhir = $this->getActiveTugasAkhirForProgressPage();

        // ✅ PERBAIKAN: Tambahkan logika untuk mengambil daftar pembimbing.
        $pembimbingList = User::role('dosen')->orderBy('name')->get(['id', 'name']);

        return [
            'tugasAkhir'      => $tugasAkhir,
            'pembimbingList'  => $pembimbingList, // Sertakan dalam data yang dikirim ke view
            'isMengajukanTA'  => (bool)$tugasAkhir,
            'progress'        => $tugasAkhir?->progress_percentage ?? 0,
        ];
    }

    /**
     * Mengambil daftar TA yang sudah dibatalkan milik mahasiswa.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCancelledTugasAkhir()
    {
        return $this->mahasiswa->tugasAkhir()
            ->where('status', TugasAkhir::STATUS_DIBATALKAN)
            ->latest()
            ->get();
    }

    /**
     * Helper untuk mengambil TA aktif milik mahasiswa.
     *
     * @return TugasAkhir|null
     */
    protected function getActiveTugasAkhir(): ?TugasAkhir
    {
        return $this->mahasiswa->tugasAkhir()->active()->first();
    }

    /**
     * Helper untuk mengambil TA aktif dengan semua relasi yang dibutuhkan di halaman progress.
     *
     * @return TugasAkhir|null
     */
    protected function getActiveTugasAkhirForProgressPage(): ?TugasAkhir
    {
        return $this->mahasiswa->tugasAkhir()
            ->active()
            ->with(['peranDosenTa.dosen.user', 'dokumenTa', 'bimbinganTa'])
            ->first();
    }
}
