<?php

namespace App\Services\Dosen;

use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use App\Models\Dosen;
use App\Models\PeranDosenTa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class BimbinganService
{
    protected Dosen $dosen;

    public function __construct()
    {
        if (Auth::check() && Auth::user()->hasRole('dosen')) {
            $this->dosen = Auth::user()->dosen;
        }
    }

    /**
     * Mengambil daftar mahasiswa bimbingan untuk dasbor dosen.
     */
    public function getFilteredMahasiswaBimbingan(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        return PeranDosenTa::query()
            ->where('dosen_id', $this->dosen->id)
            ->whereHas('tugasAkhir', fn($q) => $q->active())
            ->with(['tugasAkhir.mahasiswa.user'])
            ->latest()
            ->get();
    }

    /**
     * [DISEMPURNAKAN] Menyiapkan semua data untuk halaman detail bimbingan.
     */
    public function getDataForBimbinganDetailPage(TugasAkhir $tugasAkhir): array
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // ✅ PERBAIKAN: Eager load relasi 'peranDosenTa' untuk mencegah N+1 query
        // saat memanggil accessor pembimbingSatu/pembimbingDua.
        $tugasAkhir->load('peranDosenTa.dosen.user');

        $catatanList = CatatanBimbingan::whereIn('bimbingan_ta_id', $tugasAkhir->bimbinganTa()->pluck('id'))
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Logika penghitungan bimbingan per dosen
        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;

        $bimbinganCountP1 = $pembimbing1
            ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count()
            : 0;

        $bimbinganCountP2 = $pembimbing2
            ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count()
            : 0;

        return [
            'catatanList' => $catatanList,
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
            'pembimbing1' => $pembimbing1,
            'pembimbing2' => $pembimbing2,
        ];
    }

    /**
     * [DISEMPURNAKAN] Membuat jadwal bimbingan baru dengan aturan "Satu Sesi Aktif".
     */
    public function createJadwal(TugasAkhir $tugasAkhir, array $data): BimbinganTA
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // ✅ ATURAN BARU: Cek apakah sudah ada jadwal lain yang aktif.
        $jadwalAktifExists = $tugasAkhir->bimbinganTa()->where('status_bimbingan', 'dijadwalkan')->exists();

        if ($jadwalAktifExists) {
            throw new \Exception('Gagal membuat jadwal. Masih ada sesi bimbingan lain yang aktif dan belum selesai.');
        }

        return $tugasAkhir->bimbinganTa()->create([
            'dosen_id' => $this->dosen->id,
            'peran' => $this->getDosenRole($tugasAkhir)->peran,
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'jam_bimbingan' => $data['jam_bimbingan'],
            'status_bimbingan' => 'dijadwalkan',
            'sesi_ke' => ($tugasAkhir->bimbinganTa()->max('sesi_ke') ?? 0) + 1
        ]);
    }

    /**
     * Menyimpan catatan baru dari dosen ke log bimbingan.
     */
    public function createCatatan(TugasAkhir $tugasAkhir, array $data): CatatanBimbingan
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        $sesiBimbingan = $tugasAkhir->bimbinganTa()->where('status_bimbingan', '!=', 'selesai')->latest()->first();

        if (!$sesiBimbingan) {
            throw new \Exception('Tidak ada sesi bimbingan aktif untuk menambahkan catatan. Silakan jadwalkan sesi baru terlebih dahulu.');
        }

        return $sesiBimbingan->catatan()->create([
            'catatan'     => $data['catatan'],
            'author_type' => Dosen::class,
            'author_id'   => $this->dosen->id,
        ]);
    }

    /**
     * Menandai sesi bimbingan sebagai 'selesai'.
     */
    public function selesaikanSesi(BimbinganTA $bimbingan): void
    {
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang dijadwalkan yang bisa diselesaikan.');
        }
        $bimbingan->update(['status_bimbingan' => 'selesai']);
    }

    /**
     * Membatalkan sesi bimbingan yang sudah dijadwalkan.
     */
    public function cancelBimbingan(BimbinganTA $bimbingan): void
    {
        if (Auth::user()->dosen->id !== $bimbingan->dosen_id) {
            throw new UnauthorizedException('Anda tidak berwenang membatalkan jadwal ini.');
        }
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang dijadwalkan yang bisa dibatalkan.');
        }
        $bimbingan->update(['status_bimbingan' => 'dibatalkan']);
    }

    // --- FUNGSI HELPER OTORISASI ---
    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        if (!$tugasAkhir->peranDosenTa()->where('dosen_id', $this->dosen->id)->exists()) {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
    }

    private function getDosenRole(TugasAkhir $tugasAkhir): PeranDosenTa
    {
        return $tugasAkhir->peranDosenTa->where('dosen_id', $this->dosen->id)->firstOrFail();
    }
}
