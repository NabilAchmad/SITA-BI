<?php

namespace App\Services\Dosen;

use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use App\Models\Dosen;
use App\Models\TugasAkhir;
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
     * [DIREVISI] Mengambil daftar mahasiswa yang mengajukan bimbingan.
     * Logika ini akan menjadi dasar untuk dashboard dosen.
     */
    public function getPengajuanBimbingan()
    {
        // 1. Ambil semua ID Tugas Akhir yang dibimbing oleh dosen ini.
        $tugasAkhirIds = $this->dosen->peranDosenTa()->pluck('tugas_akhir_id');

        // 2. Cari semua Tugas Akhir tersebut yang memiliki sesi bimbingan dengan status 'diajukan'.
        return TugasAkhir::whereIn('id', $tugasAkhirIds)
            ->whereHas('bimbinganTa', function ($query) {
                $query->where('status_bimbingan', 'diajukan');
            })
            ->with('mahasiswa.user')
            ->get();
    }

    /**
     * [DIREVISI] Menyiapkan semua data untuk halaman detail bimbingan.
     */
    public function getDataForBimbinganDetailPage(TugasAkhir $tugasAkhir): array
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);
        $tugasAkhir->load('peranDosenTa.dosen.user', 'mahasiswa.user');

        // Ambil sesi bimbingan yang sedang aktif (diajukan atau dijadwalkan)
        $sesiAktif = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->first(); // Hanya akan ada satu sesi aktif pada satu waktu

        // Ambil semua catatan dari semua sesi untuk tugas akhir ini
        $catatanList = CatatanBimbingan::whereIn('bimbingan_ta_id', $tugasAkhir->bimbinganTa()->pluck('id'))
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'tugasAkhir' => $tugasAkhir,
            'sesiAktif' => $sesiAktif,
            'catatanList' => $catatanList,
            'pembimbing1' => $tugasAkhir->pembimbingSatu,
            'pembimbing2' => $tugasAkhir->pembimbingDua,
        ];
    }

    /**
     * [FUNGSI BARU] Mengatur jadwal untuk sesi bimbingan yang diajukan mahasiswa.
     * Fungsi ini akan meng-update record yang sudah ada, bukan membuat baru.
     */
    public function setJadwal(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // 1. Validasi: Pastikan tidak ada jadwal lain yang sudah aktif.
        if ($tugasAkhir->bimbinganTa()->where('status_bimbingan', 'dijadwalkan')->exists()) {
            throw new \Exception('Gagal mengatur jadwal. Jadwal sudah ditentukan oleh pembimbing lain.');
        }

        // 2. Cari semua sesi yang 'diajukan' untuk TA ini (seharusnya ada 2, satu per dosen).
        $pengajuanBimbingan = $tugasAkhir->bimbinganTa()->where('status_bimbingan', 'diajukan');

        if ($pengajuanBimbingan->count() === 0) {
            throw new \Exception('Tidak ada pengajuan bimbingan yang aktif dari mahasiswa.');
        }

        // 3. Update semua record yang 'diajukan' menjadi 'dijadwalkan' dengan tanggal & jam baru.
        $pengajuanBimbingan->update([
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'jam_bimbingan'     => $data['jam_bimbingan'],
            'status_bimbingan'  => 'dijadwalkan',
        ]);
    }

    /**
     * [DIREVISI] Menyimpan catatan baru dari dosen ke sesi bimbingan yang relevan.
     */
    public function createCatatan(TugasAkhir $tugasAkhir, array $data): CatatanBimbingan
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // 1. Cari sesi bimbingan yang aktif (diajukan/dijadwalkan) untuk dosen yang sedang login.
        $sesiBimbinganDosen = $tugasAkhir->bimbinganTa()
            ->where('dosen_id', $this->dosen->id)
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->latest()
            ->first();

        if (!$sesiBimbinganDosen) {
            throw new \Exception('Tidak ada sesi bimbingan aktif untuk menambahkan catatan.');
        }

        // 2. Simpan catatan ke sesi tersebut.
        return $sesiBimbinganDosen->catatan()->create([
            'catatan'     => $data['catatan'],
            'author_type' => Dosen::class,
            'author_id'   => $this->dosen->id,
        ]);
    }

    /**
     * [DIREVISI] Menandai sesi bimbingan sebagai 'selesai' hanya untuk dosen yang login.
     */
    public function selesaikanSesi(BimbinganTA $bimbingan): void
    {
        // Otorisasi: Pastikan dosen yang login adalah pemilik record bimbingan ini.
        if ($bimbingan->dosen_id !== $this->dosen->id) {
            throw new UnauthorizedException('Anda tidak berwenang menyelesaikan sesi bimbingan ini.');
        }
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang dijadwalkan yang bisa diselesaikan.');
        }

        // Update status hanya untuk record milik dosen ini.
        $bimbingan->update(['status_bimbingan' => 'selesai']);
    }

    /**
     * [DIREVISI] Membatalkan sesi bimbingan untuk kedua dosen.
     */
    public function cancelBimbingan(BimbinganTA $bimbingan): void
    {
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);

        // 1. Cari semua sesi yang dijadwalkan dengan nomor sesi yang sama.
        $sesiTerkait = $bimbingan->tugasAkhir->bimbinganTa()
            ->where('sesi_ke', $bimbingan->sesi_ke)
            ->where('status_bimbingan', 'dijadwalkan');

        if ($sesiTerkait->count() === 0) {
            throw new \Exception('Tidak ada jadwal aktif untuk dibatalkan.');
        }

        // 2. Batalkan semua sesi terkait (untuk kedua dosen).
        $sesiTerkait->update(['status_bimbingan' => 'dibatalkan']);
    }

    /**
     * Helper untuk otorisasi.
     */
    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        if (!$tugasAkhir->peranDosenTa()->where('dosen_id', $this->dosen->id)->exists()) {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
    }
}
