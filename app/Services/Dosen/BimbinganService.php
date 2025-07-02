<?php

namespace App\Services\Dosen;

use App\Models\BimbinganTA;
use App\Models\Dosen;
use App\Models\HistoryPerubahanJadwal;
use App\Models\PeranDosenTa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class BimbinganService
{
    protected Dosen $dosen;

    public function __construct()
    {
        $this->dosen = Auth::user()->dosen;
    }

    /**
     * Mengambil daftar mahasiswa bimbingan dengan filter pencarian & prodi.
     */
    public function getFilteredMahasiswaBimbingan(Request $request): \Illuminate\Database\Eloquent\Collection
    {
        $query = PeranDosenTa::query()
            ->where('dosen_id', $this->dosen->id)
            ->whereIn('peran', [PeranDosenTa::PERAN_PEMBIMBING_1, PeranDosenTa::PERAN_PEMBIMBING_2])
            ->whereHas('tugasAkhir', function ($q) {
                $q->active();
            });

        if ($request->filled('prodi')) {
            $query->whereHas('tugasAkhir.mahasiswa', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('tugasAkhir.mahasiswa.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return $query->with([
            'tugasAkhir.mahasiswa.user'
        ])
            ->latest()
            ->get();
    }

    /**
     * PERBAIKAN: Mengambil data detail Tugas Akhir berdasarkan ID Mahasiswa.
     * Method ini sekarang bertanggung jawab untuk menemukan TA yang benar DAN melakukan otorisasi.
     *
     * @param int $mahasiswaId
     * @return TugasAkhir
     * @throws \Exception
     */
    public function getTugasAkhirDetailForMahasiswa(int $mahasiswaId): TugasAkhir
    {
        // 1. Cari tugas akhir yang AKTIF milik mahasiswa dengan ID yang diberikan.
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswaId)
            ->active() // Menggunakan scope untuk memastikan hanya TA aktif yang diambil
            ->latest()
            ->first();

        // 2. Jika tidak ada tugas akhir aktif, lempar error.
        if (!$tugasAkhir) {
            throw new \Exception('Tugas akhir aktif untuk mahasiswa ini tidak ditemukan.');
        }

        // 3. Lakukan otorisasi untuk memastikan dosen yang login adalah pembimbing dari TA ini.
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // 4. Jika lolos, muat relasi yang dibutuhkan oleh view dan kembalikan.
        return $tugasAkhir->load([
            'mahasiswa.user',
            'revisiTa',
            'bimbinganTa' => fn($q) => $q->where('dosen_id', $this->dosen->id)->with('catatanBimbingan')->latest()
        ]);
    }

    /**
     * Menyetujui atau menolak sebuah sesi bimbingan.
     */
    public function updateBimbinganStatus(BimbinganTA $bimbingan, string $status, ?string $catatan = null): BimbinganTA
    {
        $this->authorizeDosenForBimbingan($bimbingan);

        $bimbingan->status_bimbingan = $status;
        $bimbingan->save();

        if ($status === BimbinganTA::STATUS_DITOLAK && $catatan) {
            $bimbingan->catatanBimbingan()->create([
                'catatan'     => $catatan,
                'author_type' => 'dosen',
                'author_id'   => Auth::id(),
            ]);
        }
        return $bimbingan;
    }

    /**
     * Menyetujui perubahan jadwal.
     */
    public function approveScheduleChange(HistoryPerubahanJadwal $perubahan): void
    {
        $this->authorizeDosenForBimbingan($perubahan->bimbingan);

        DB::transaction(function () use ($perubahan) {
            $perubahan->update(['status' => 'disetujui']);
            $perubahan->bimbingan()->update([
                'tanggal_bimbingan' => $perubahan->tanggal_baru,
                'jam_bimbingan'     => $perubahan->jam_baru,
            ]);
        });
    }

    // BARU: Metode untuk menolak perubahan jadwal
    public function rejectScheduleChange(HistoryPerubahanJadwal $perubahan, string $catatan): void
    {
        $this->authorizeDosenForBimbingan($perubahan->bimbingan);

        DB::transaction(function () use ($perubahan, $catatan) {
            $perubahan->update([
                'status' => 'ditolak',
                'catatan_penolakan' => $catatan
            ]);
        });
    }

    /**
     * Menyetujui permintaan pembatalan Tugas Akhir.
     */
    public function approveThesisCancellation(TugasAkhir $tugasAkhir): string
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        if ($tugasAkhir->status !== TugasAkhir::STATUS_MENUNGGU_PEMBATALAN) {
            throw new \Exception('Status TA tidak valid untuk aksi ini.');
        }

        $peran = $this->getDosenRole($tugasAkhir);
        $peran->update(['setuju_pembatalan' => 'ya', 'tanggal_verifikasi' => now()]);

        $tugasAkhir->refresh();
        $pembimbingRoles = $tugasAkhir->peranDosenTa->whereIn('peran', ['pembimbing1', 'pembimbing2']);

        if ($pembimbingRoles->every(fn($p) => $p->setuju_pembatalan === 'ya')) {
            DB::transaction(function () use ($tugasAkhir, $pembimbingRoles) {
                $tugasAkhir->update(['status' => TugasAkhir::STATUS_DIBATALKAN]);
                $pembimbingRoles->each->delete();
            });
            return 'dibatalkan';
        }

        return 'menunggu_pembimbing_lain';
    }

    /**
     * Menolak permintaan pembatalan Tugas Akhir.
     */
    public function rejectThesisCancellation(TugasAkhir $tugasAkhir, string $catatanPenolakan): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        if ($tugasAkhir->status !== TugasAkhir::STATUS_MENUNGGU_PEMBATALAN) {
            throw new \Exception('Status TA tidak valid untuk aksi ini.');
        }

        DB::transaction(function () use ($tugasAkhir, $catatanPenolakan) {
            $tugasAkhir->update(['status' => TugasAkhir::STATUS_DISETUJUI, 'alasan_pembatalan' => null]);
            $tugasAkhir->peranDosenTa()
                ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                ->update([
                    'setuju_pembatalan'  => null,
                    'tanggal_verifikasi' => null,
                    'catatan_verifikasi' => $catatanPenolakan,
                ]);
        });
    }

    private function authorizeDosenForBimbingan(BimbinganTA $bimbingan): void
    {
        if ($bimbingan->dosen_id !== $this->dosen->id) {
            throw new UnauthorizedException('Anda tidak memiliki akses ke sesi bimbingan ini.');
        }
    }

    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        $isPembimbing = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $this->dosen->id)
            ->whereIn('peran', [PeranDosenTa::PERAN_PEMBIMBING_1, PeranDosenTa::PERAN_PEMBIMBING_2])
            ->exists();

        if (!$isPembimbing) {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
    }

    private function getDosenRole(TugasAkhir $tugasAkhir): PeranDosenTa
    {
        $peran = $tugasAkhir->peranDosenTa->where('dosen_id', $this->dosen->id)->first();
        if (!$peran) {
            throw new \Exception('Peran Dosen tidak ditemukan.');
        }
        return $peran;
    }
}
