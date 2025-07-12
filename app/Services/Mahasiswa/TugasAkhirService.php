<?php

namespace App\Services\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\User; // ✅ PERBAIKAN: Import model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\DokumenTa;
use Illuminate\Support\Facades\DB; // <-- 1. Pastikan DB di-import
use App\Models\CatatanBimbingan;

class TugasAkhirService
{
    protected ?Mahasiswa $mahasiswa;

    /**
     * Service ini selalu bekerja dalam konteks mahasiswa yang sedang login.
     */
    public function __construct()
    {
        // Konstruktor yang aman untuk memastikan service hanya bekerja untuk mahasiswa.
        if (Auth::check() && Auth::user()->hasRole('mahasiswa')) {
            $this->mahasiswa = Auth::user()->mahasiswa;
        } else {
            // Jika bukan mahasiswa, properti akan null, dan metode akan gagal dengan aman.
            $this->mahasiswa = null;
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

            // Langkah 1: Simpan File & Dokumen.
            $filePath = $file->store("dokumen_ta/{$tugasAkhir->id}", 'public');
            $tugasAkhir->update(['file_path' => $filePath]);

            $dokumen = $tugasAkhir->dokumenTa()->create([
                'tipe_dokumen' => $tipeDokumen,
                'file_path'    => $filePath,
            ]);

            // Langkah 2: Buat "Jejak" di Log Bimbingan.
            $sesiBimbingan = $tugasAkhir->bimbinganTa()->latest()->first();

            if (!$sesiBimbingan) {
                throw new \Exception('Tidak bisa mengunggah file. Belum ada sesi bimbingan yang dibuat oleh dosen.');
            }

            // Buat catatan "UPLOAD" otomatis.
            $sesiBimbingan->catatan()->create([
                'catatan'     => "UPLOAD_ID:" . $dokumen->id,
                'author_type' => Mahasiswa::class,
                'author_id'   => $this->mahasiswa->id,
            ]);

            // Jika ada catatan manual, simpan juga sebagai entri terpisah.
            if (!empty($catatan)) {
                $sesiBimbingan->catatan()->create([
                    'catatan'     => $catatan,
                    'author_type' => Mahasiswa::class,
                    'author_id'   => $this->mahasiswa->id,
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
        if (!$this->mahasiswa) {
            return $this->getEmptyProgressData();
        }

        // Cari tugas akhir yang aktif milik mahasiswa yang login dengan semua relasi penting.
        $tugasAkhir = $this->mahasiswa->tugasAkhir()
            ->active()
            // ✅ PERBAIKAN: Memuat relasi 'peranDosenTa' yang sebenarnya, bukan accessor.
            // Ini akan menyelesaikan error "undefined relationship" dan mencegah N+1 query.
            ->with(['mahasiswa.user', 'peranDosenTa.dosen.user'])
            ->latest()
            ->first();

        // Jika tidak ada TA aktif, kembalikan struktur data kosong.
        if (!$tugasAkhir) {
            return $this->getEmptyProgressData();
        }

        // Ambil semua catatan untuk log bimbingan.
        $catatanList = CatatanBimbingan::whereIn(
            'bimbingan_ta_id',
            $tugasAkhir->bimbinganTa()->pluck('id')
        )
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung jumlah bimbingan yang selesai untuk setiap pembimbing.
        // Kode ini sekarang akan berjalan efisien karena 'peranDosenTa' sudah di-eager load.
        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;

        $bimbinganCountP1 = $pembimbing1
            ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count()
            : 0;

        $bimbinganCountP2 = $pembimbing2
            ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count()
            : 0;

        return [
            'tugasAkhir'       => $tugasAkhir,
            'catatanList'      => $catatanList,
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
            'pembimbing1'      => $pembimbing1,
            'pembimbing2'      => $pembimbing2,
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
     * [BARU] Menyimpan catatan dari mahasiswa.
     *
     * @param TugasAkhir $tugasAkhir
     * @param array $data
     * @return CatatanBimbingan
     * @throws \Exception
     */
    public function createCatatanForMahasiswa(TugasAkhir $tugasAkhir, array $data): CatatanBimbingan
    {
        // Pastikan mahasiswa yang login adalah pemilik TA ini
        if (Auth::user()->mahasiswa->id !== $tugasAkhir->mahasiswa_id) {
            throw new \Illuminate\Validation\UnauthorizedException('Anda tidak berwenang untuk tugas akhir ini.');
        }

        // Cari sesi bimbingan terakhir yang aktif untuk menampung catatan ini
        $sesiBimbingan = $tugasAkhir->bimbinganTa()->where('status_bimbingan', '!=', 'selesai')->latest()->first();

        // Jika tidak ada sesi aktif, berikan error yang jelas
        if (!$sesiBimbingan) {
            throw new \Exception('Tidak bisa mengirim catatan. Belum ada sesi bimbingan yang dijadwalkan oleh dosen.');
        }

        // Simpan catatan dengan author adalah mahasiswa yang sedang login
        return $sesiBimbingan->catatan()->create([
            'catatan'     => $data['catatan'],
            'author_type' => Mahasiswa::class,
            'author_id'   => Auth::user()->mahasiswa->id,
        ]);
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

    private function getEmptyProgressData(): array
    {
        return [
            'tugasAkhir' => null,
            'catatanList' => collect(),
            'bimbinganCountP1' => 0,
            'bimbinganCountP2' => 0,
            'pembimbing1' => null,
            'pembimbing2' => null,
        ];
    }
}
