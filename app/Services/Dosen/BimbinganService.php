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

    // Metode ini sudah benar
    public function getFilteredMahasiswaBimbingan(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        // ... (kode Anda tidak berubah)
        return PeranDosenTa::query()
            ->where('dosen_id', $this->dosen->id)
            ->whereHas('tugasAkhir', fn($q) => $q->active())
            ->with(['tugasAkhir.mahasiswa.user'])
            ->latest()
            ->get();
    }

    // Metode ini sudah benar
    public function getDataForBimbinganDetailPage(TugasAkhir $tugasAkhir): array
    {
        // ... (kode Anda tidak berubah)
        $this->authorizeDosenIsPembimbing($tugasAkhir);
        $catatanList = CatatanBimbingan::whereIn(
            'bimbingan_ta_id',
            $tugasAkhir->bimbinganTa()->pluck('id')
        )
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();
        $bimbinganCount = $tugasAkhir->bimbinganTa()
            ->where('status_bimbingan', 'selesai')
            ->count();
        return [
            'catatanList' => $catatanList,
            'bimbinganCount' => $bimbinganCount,
        ];
    }

    /**
     * [PERBAIKAN KECIL] Menyimpan catatan baru dari dosen.
     * Logika diubah agar tidak membuat sesi baru secara otomatis.
     */
    public function createCatatan(TugasAkhir $tugasAkhir, array $data): CatatanBimbingan
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // Cari sesi bimbingan terakhir yang aktif (dijadwalkan atau berjalan).
        $sesiBimbingan = $tugasAkhir->bimbinganTa()->where('status_bimbingan', '!=', 'selesai')->latest()->first();

        // Jika tidak ada sesi aktif sama sekali, berikan error.
        if (!$sesiBimbingan) {
            throw new \Exception('Tidak ada sesi bimbingan aktif untuk menambahkan catatan. Silakan jadwalkan sesi baru terlebih dahulu.');
        }

        return $sesiBimbingan->catatan()->create([
            'catatan'     => $data['catatan'],
            'author_type' => Dosen::class,
            'author_id'   => $this->dosen->id,
        ]);
    }

    // Metode ini sudah benar
    public function createJadwal(TugasAkhir $tugasAkhir, array $data): BimbinganTA
    {
        // ... (kode Anda tidak berubah)
        $this->authorizeDosenIsPembimbing($tugasAkhir);
        return $tugasAkhir->bimbinganTa()->create([
            'dosen_id' => $this->dosen->id,
            'peran' => $this->getDosenRole($tugasAkhir)->peran,
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'jam_bimbingan' => $data['jam_bimbingan'],
            'status_bimbingan' => 'dijadwalkan',
            'sesi_ke' => ($tugasAkhir->bimbinganTa()->max('sesi_ke') ?? 0) + 1
        ]);
    }

    // Metode ini sudah benar
    public function selesaikanSesi(BimbinganTA $bimbingan): void
    {
        // ... (kode Anda tidak berubah)
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang dijadwalkan yang bisa diselesaikan.');
        }
        $bimbingan->update(['status_bimbingan' => 'selesai']);
    }

    // --- FUNGSI HELPER OTORISASI ---
    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        // ... (kode Anda tidak berubah)
        if (!$tugasAkhir->peranDosenTa()->where('dosen_id', $this->dosen->id)->exists()) {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
    }

    private function getDosenRole(TugasAkhir $tugasAkhir): PeranDosenTa
    {
        // ... (kode Anda tidak berubah)
        return $tugasAkhir->peranDosenTa->where('dosen_id', $this->dosen->id)->firstOrFail();
    }
}
