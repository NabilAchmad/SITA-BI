<?php

namespace App\Services\Mahasiswa;

use App\Models\BimbinganTA;
use App\Models\DokumenTa;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Service ini mengelola semua logika bisnis terkait Tugas Akhir dari sisi Mahasiswa.
 */
class TugasAkhirService
{
    protected ?Mahasiswa $mahasiswa;

    public function __construct()
    {
        if (Auth::check() && Auth::user()->hasRole('mahasiswa')) {
            $this->mahasiswa = Auth::user()->mahasiswa;
        } else {
            $this->mahasiswa = null;
        }
    }

    /**
     * [DIREVISI FINAL] Menangani upload file.
     * - Membuat DUA record bimbingan, satu untuk setiap pembimbing.
     * - Memastikan tanggal bimbingan NULL saat dibuat.
     * - Mengizinkan penggantian file jika sesi masih 'diajukan'.
     */
    public function ajukanBimbinganDenganFile(TugasAkhir $tugasAkhir, UploadedFile $file, string $tipeDokumen, ?string $catatan = null): void
    {
        if (optional($this->mahasiswa)->id !== $tugasAkhir->mahasiswa_id) {
            throw new UnauthorizedException('Anda tidak berwenang untuk tugas akhir ini.');
        }

        if ($tugasAkhir->bimbinganTa()->where('status_bimbingan', 'dijadwalkan')->exists()) {
            throw new \Exception('Gagal mengunggah file baru. Sesi bimbingan sudah dijadwalkan oleh dosen.');
        }

        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;

        if (!$pembimbing1 || !$pembimbing2) {
            throw new \Exception('Tidak dapat mengajukan bimbingan. Dosen Pembimbing 1 dan 2 harus ditetapkan terlebih dahulu.');
        }

        $mahasiswaId = $tugasAkhir->mahasiswa_id;

        DB::transaction(function () use ($tugasAkhir, $file, $tipeDokumen, $catatan, $pembimbing1, $pembimbing2, $mahasiswaId) {

            // ✅ PERBAIKAN: Logika diubah untuk memastikan kedua sesi bimbingan selalu ada.

            $sesiKe = ($tugasAkhir->bimbinganTa()->where('status_bimbingan', 'selesai')->distinct('sesi_ke')->count()) + 1;

            $dataBimbingan = [
                'sesi_ke'           => $sesiKe,
                'status_bimbingan'  => 'diajukan',
                'tanggal_bimbingan' => null,
                'jam_bimbingan'     => null,
            ];

            // Cari atau buat sesi untuk Pembimbing 1
            $sesiBimbinganP1 = $tugasAkhir->bimbinganTa()->firstOrCreate(
                ['dosen_id' => $pembimbing1->dosen_id, 'status_bimbingan' => 'diajukan'],
                array_merge($dataBimbingan, ['peran' => $pembimbing1->peran])
            );

            // Cari atau buat sesi untuk Pembimbing 2
            $sesiBimbinganP2 = $tugasAkhir->bimbinganTa()->firstOrCreate(
                ['dosen_id' => $pembimbing2->dosen_id, 'status_bimbingan' => 'diajukan'],
                array_merge($dataBimbingan, ['peran' => $pembimbing2->peran])
            );

            // Hapus dokumen lama yang terkait dengan tugas akhir ini jika ada.
            $dokumenLama = $tugasAkhir->dokumenTa()->latest()->first();
            if ($dokumenLama) {
                Storage::disk('public')->delete($dokumenLama->file_path);
                $dokumenLama->forceDelete();
            }

            // Simpan file dan buat record dokumen yang BARU.
            $latestVersion = ($tugasAkhir->dokumenTa()->where('tipe_dokumen', $tipeDokumen)->max('version') ?? 0) + 1;
            // Gunakan nomor sesi sebagai referensi folder.
            $filePath = $file->store("dokumen_ta/{$tugasAkhir->id}/sesi_{$sesiBimbinganP1->sesi_ke}", 'public');
            $tugasAkhir->dokumenTa()->create([
                'tipe_dokumen'   => $tipeDokumen,
                'file_path'      => $filePath,
                'version'        => $latestVersion,
            ]);

            // Simpan catatan baru jika ada.
            // Dengan logika firstOrCreate, $sesiBimbinganP1 dan $sesiBimbinganP2 dijamin tidak akan null.
            if (!empty($catatan)) {
                $dataCatatan = [
                    'catatan'     => $catatan,
                    'author_type' => Mahasiswa::class,
                    'author_id'   => $mahasiswaId,
                ];
                $sesiBimbinganP1->catatan()->create($dataCatatan);
                $sesiBimbinganP2->catatan()->create($dataCatatan);
            }
        });
    }

    // revisi bagian model
    public function createCatatanForMahasiswa(TugasAkhir $tugasAkhir, array $data)
    {
        // 1. Otorisasi: Pastikan mahasiswa yang login adalah pemilik TA ini.
        if (optional($this->mahasiswa)->id !== $tugasAkhir->mahasiswa_id) {
            throw new UnauthorizedException('Anda tidak berwenang untuk tugas akhir ini.');
        }

        // 2. Cari Sesi Aktif: Temukan sesi bimbingan terakhir yang statusnya masih 'diajukan' atau 'dijadwalkan'.
        // Catatan hanya bisa ditambahkan ke sesi yang sedang berjalan.
        $sesiBimbingan = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->latest()
            ->first();

        // 3. Validasi: Jika tidak ada sesi aktif, lemparkan error yang jelas.
        if (!$sesiBimbingan) {
            throw new \Exception('Tidak bisa mengirim catatan. Tidak ada sesi bimbingan yang sedang aktif menunggu jadwal dari dosen.');
        }

        // 4. Simpan ke Model yang Benar: Membuat record baru pada relasi 'catatan()' 
        //    yang ada di model BimbinganTA.
        return $sesiBimbingan->catatan()->create([
            'catatan'     => $data['catatan'],
            // Menggunakan polymorphic relationship untuk mencatat siapa pengirimnya.
            'author_type' => Mahasiswa::class,
            'author_id'   => $this->mahasiswa->id,
        ]);
    }

    /**
     * Membuat data Tugas Akhir baru.
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
     * [FUNGSI DITAMBAHKAN KEMBALI] Mengambil data untuk halaman dashboard mahasiswa.
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
     * Mengambil data untuk halaman progress tugas akhir.
     */
    public function getProgressPageData(): array
    {
        if (!$this->mahasiswa) {
            return $this->getEmptyProgressData();
        }

        $tugasAkhir = $this->getActiveTugasAkhirForProgressPage();

        if (!$tugasAkhir) {
            return $this->getEmptyProgressData();
        }

        // ✅ PERBAIKAN: Ambil sesi bimbingan yang paling baru.
        // Prioritaskan yang statusnya masih aktif (diajukan/dijadwalkan).
        $sesiBimbinganTerbaru = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->latest()
            ->first();

        // Jika tidak ada yang aktif, ambil saja yang terakhir dibuat (misal: sudah selesai).
        if (!$sesiBimbinganTerbaru) {
            $sesiBimbinganTerbaru = $tugasAkhir->bimbinganTa()->latest()->first();
        }

        // ✅ PERBAIKAN: Ambil catatan HANYA dari sesi terbaru tersebut.
        $catatanList = $sesiBimbinganTerbaru
            ? $sesiBimbinganTerbaru->catatan()->with('author.user')->orderBy('created_at', 'asc')->get()
            : collect();

        // ✅ BARU: Ambil dokumen yang paling terakhir diunggah secara terpisah.
        $dokumenTerbaru = $tugasAkhir->dokumenTa()->latest()->first();

        // Penghitungan jumlah bimbingan tetap sama.
        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;
        $bimbinganCountP1 = $pembimbing1 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;
        $bimbinganCountP2 = $pembimbing2 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;

        return [
            'tugasAkhir'       => $tugasAkhir,
            'dokumenTerbaru'   => $dokumenTerbaru, // Kirim data dokumen terbaru ke view
            'catatanList'      => $catatanList,    // Kirim catatan dari sesi terakhir
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
            'pembimbing1'      => $pembimbing1,
            'pembimbing2'      => $pembimbing2,
        ];
    }

    /**
     * Mengajukan pembatalan Tugas Akhir.
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
     * [FUNGSI DITAMBAHKAN KEMBALI] Mengambil daftar TA yang sudah dibatalkan milik mahasiswa.
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
     */
    protected function getActiveTugasAkhir(): ?TugasAkhir
    {
        return $this->mahasiswa->tugasAkhir()->active()->first();
    }

    /**
     * Helper untuk mengambil TA aktif dengan semua relasi yang dibutuhkan.
     */
    protected function getActiveTugasAkhirForProgressPage(): ?TugasAkhir
    {
        return $this->mahasiswa->tugasAkhir()
            ->active()
            ->with(['peranDosenTa.dosen.user', 'dokumenTa', 'bimbinganTa'])
            ->first();
    }

    /**
     * Helper untuk data kosong.
     */
    private function getEmptyProgressData(): array
    {
        return [
            'tugasAkhir'       => null,
            'catatanList'      => collect(),
            'bimbinganCountP1' => 0,
            'bimbinganCountP2' => 0,
            'pembimbing1'      => null,
            'pembimbing2'      => null,
        ];
    }
}
