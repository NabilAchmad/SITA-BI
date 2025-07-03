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
        // Mengambil model Dosen dari user yang sedang login saat service diinisialisasi.
        // Ini adalah praktik yang baik untuk menghindari query berulang.
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
     * Mengambil data detail Tugas Akhir berdasarkan ID Mahasiswa.
     */
    public function getTugasAkhirDetailForMahasiswa(int $mahasiswaId): TugasAkhir
    {
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswaId)
            ->active()
            ->latest()
            ->firstOrFail();

        $this->authorizeDosenIsPembimbing($tugasAkhir);

        return $tugasAkhir->load([
            'mahasiswa.user',
            'revisiTa',
            // Memuat bimbingan khusus untuk dosen yang login, beserta catatannya.
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

        // Logika ini sudah benar: catatan hanya dibuat saat statusnya DITOLAK.
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
     * Menandai sesi bimbingan sebagai selesai dan mencatat nomor sesinya.
     * FUNGSI INI TELAH DIPERBAIKI SECARA SIGNIFIKAN.
     */
    public function selesaikanBimbingan(BimbinganTA $bimbingan): BimbinganTA
    {
        $this->authorizeDosenForBimbingan($bimbingan);

        // Validasi: Hanya bimbingan yang sudah disetujui yang bisa diselesaikan.
        if ($bimbingan->status_bimbingan !== BimbinganTA::STATUS_DISETUJUI) {
            throw new \Exception('Hanya bimbingan yang sudah disetujui yang dapat ditandai selesai.');
        }

        // Gunakan Transaction untuk memastikan konsistensi data.
        return DB::transaction(function () use ($bimbingan) {
            // 1. Hitung nilai 'sesi_ke' berikutnya.
            //    Cari nilai 'sesi_ke' tertinggi untuk tugas akhir ini yang sudah selesai.
            $maxSesi = BimbinganTA::where('tugas_akhir_id', $bimbingan->tugas_akhir_id)
                ->where('status_bimbingan', BimbinganTA::STATUS_SELESAI)
                ->max('sesi_ke');

            // 2. Update status bimbingan menjadi 'selesai' dan set nilai 'sesi_ke'.
            //    Jika belum ada sesi yang selesai (maxSesi = null), maka ini adalah sesi ke-1.
            $bimbingan->sesi_ke = ($maxSesi ?? 0) + 1;
            $bimbingan->status_bimbingan = BimbinganTA::STATUS_SELESAI;
            $bimbingan->save();

            return $bimbingan;
        });
    }

    /**
     * Menyetujui perubahan jadwal.
     */
    public function approveScheduleChange(HistoryPerubahanJadwal $perubahan): void
    {
        // Pengecekan ini penting untuk mencegah error jika relasi bimbingan null.
        if (!$perubahan->bimbingan) {
            throw new \Exception('Data histori perubahan jadwal ini tidak terhubung dengan bimbingan manapun.');
        }

        $this->authorizeDosenForBimbingan($perubahan->bimbingan);

        DB::transaction(function () use ($perubahan) {
            // Update status histori
            $perubahan->update(['status' => 'disetujui']);

            // Update data bimbingan utama
            $perubahan->bimbingan()->update([
                'tanggal_bimbingan' => $perubahan->tanggal_baru,
                'jam_bimbingan'     => $perubahan->jam_baru,
            ]);

            // Batalkan permintaan lain yang tertunda untuk bimbingan yang sama.
            HistoryPerubahanJadwal::where('bimbingan_ta_id', $perubahan->bimbingan_ta_id)
                ->where('id', '!=', $perubahan->id)
                ->where('status', 'menunggu')
                ->update(['status' => 'dibatalkan']);
        });
    }

    /**
     * Menolak perubahan jadwal.
     */
    public function rejectScheduleChange(HistoryPerubahanJadwal $perubahan, string $catatan): void
    {
        if (!$perubahan->bimbingan) {
            throw new \Exception('Data histori perubahan jadwal ini tidak terhubung dengan bimbingan manapun.');
        }

        $this->authorizeDosenForBimbingan($perubahan->bimbingan);

        // Menggunakan transaction untuk konsistensi, meskipun hanya satu aksi.
        DB::transaction(function () use ($perubahan, $catatan) {
            $perubahan->update([
                'status' => 'ditolak',
                'alasan_perubahan' => $catatan // Pastikan kolom ini ada di $fillable model HistoryPerubahanJadwal
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

        // Jika semua pembimbing sudah setuju
        if ($pembimbingRoles->every(fn($p) => $p->setuju_pembatalan === 'ya')) {
            DB::transaction(function () use ($tugasAkhir, $pembimbingRoles) {
                $tugasAkhir->update(['status' => TugasAkhir::STATUS_DIBATALKAN]);
                // Hapus peran pembimbing dari TA yang dibatalkan
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
            // Kembalikan status TA ke disetujui dan hapus alasan pembatalan
            $tugasAkhir->update(['status' => TugasAkhir::STATUS_DISETUJUI, 'alasan_pembatalan' => null]);

            // Reset status persetujuan pembatalan dari semua pembimbing
            $tugasAkhir->peranDosenTa()
                ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                ->update([
                    'setuju_pembatalan'  => null,
                    'tanggal_verifikasi' => null,
                    'catatan_verifikasi' => $catatanPenolakan, // Simpan catatan penolakan
                ]);
        });
    }

    // --- FUNGSI HELPER UNTUK OTORISASI ---

    /**
     * PERBAIKAN FINAL DI SINI:
     * Secara eksplisit mencari Tugas Akhir untuk memastikan data yang valid.
     */
    private function authorizeDosenForBimbingan(BimbinganTA $bimbingan): void
    {
        $bimbingan->refresh();

        if (empty($bimbingan->tugas_akhir_id)) {
            throw new \Exception('Otorisasi gagal: Kolom tugas_akhir_id kosong pada bimbingan ID: ' . $bimbingan->id);
        }

        $tugasAkhir = TugasAkhir::find($bimbingan->tugas_akhir_id);

        if (!$tugasAkhir) {
            throw new \Exception(
                'Otorisasi gagal: Data bimbingan ini merujuk ke Tugas Akhir yang tidak dapat ditemukan (ID: ' . $bimbingan->tugas_akhir_id . ').'
            );
        }

        $this->authorizeDosenIsPembimbing($tugasAkhir);
    }

    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        $isPembimbing = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $this->dosen->id)
            ->whereIn('peran', [PeranDosenTa::PERAN_PEMBIMBING_1, PeranDosenTa::PERAN_PEMBIMBING_2])
            ->exists();

        if (!$isPembimbing) {
            // Pesan error diubah agar lebih sesuai dengan konteks pengecekan
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
