<?php

namespace App\Services\Dosen;

use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\DB; // Import DB Facade untuk transaction
use Illuminate\Support\Facades\Log;

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
     * [DIREVISI] Mengambil daftar mahasiswa yang telah mengajukan bimbingan
     * dan menunggu jadwal dari dosen.
     */
    public function getPengajuanBimbingan(Request $request)
    {
        // 1. Ambil semua ID Tugas Akhir yang dibimbing oleh dosen ini.
        $tugasAkhirIds = $this->dosen->peranDosenTa()->pluck('tugas_akhir_id');

        // 2. Cari semua Tugas Akhir tersebut yang memiliki sesi bimbingan dengan status 'diajukan'.
        //    Kita gunakan distinct() agar setiap mahasiswa hanya muncul sekali meskipun ada 2 record 'diajukan'.
        return TugasAkhir::whereIn('id', $tugasAkhirIds)
            ->whereHas('bimbinganTa')
            ->with('mahasiswa.user')
            ->distinct()
            ->get();
    }

    /**
     * [DIREVISI] Menyiapkan semua data yang dibutuhkan untuk halaman detail bimbingan.
     */
    public function getDataForBimbinganDetailPage(TugasAkhir $tugasAkhir): array
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);
        $tugasAkhir->load('peranDosenTa.dosen.user', 'mahasiswa.user');

        $loggedInDosenId = $this->dosen->id;

        $sesiAktif = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->get();

        // ✅ PERBAIKAN UTAMA: Ambil semua catatan, lalu filter untuk privasi.
        $allCatatan = CatatanBimbingan::whereIn('bimbingan_ta_id', $tugasAkhir->bimbinganTa()->pluck('id'))
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();

        // 1. Filter untuk menampilkan hanya catatan dari mahasiswa dan dosen yang login.
        $filteredCatatan = $allCatatan->filter(function ($catatan) use ($loggedInDosenId) {
            // Selalu tampilkan catatan mahasiswa
            if ($catatan->author_type === 'App\Models\Mahasiswa') {
                return true;
            }
            // Hanya tampilkan catatan dari dosen yang sedang login
            if ($catatan->author_type === 'App\Models\Dosen' && $catatan->author_id === $loggedInDosenId) {
                return true;
            }
            // Sembunyikan catatan dari dosen lain
            return false;
        });

        // 2. Hapus duplikat (terutama untuk catatan mahasiswa yang tersimpan dua kali).
        $catatanList = $filteredCatatan->unique(function ($item) {
            return $item['created_at']->toDateTimeString() . $item['catatan'] . $item['author_id'];
        });

        $riwayatDokumen = $tugasAkhir->dokumenTa()->orderBy('created_at', 'asc')->get();
        $dokumenTerbaru = $riwayatDokumen->last();

        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;
        $bimbinganCountP1 = $pembimbing1 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;
        $bimbinganCountP2 = $pembimbing2 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;

        return [
            'tugasAkhir' => $tugasAkhir,
            'sesiAktif' => $sesiAktif,
            'catatanList' => $catatanList, // <-- Data ini sekarang sudah bersih dan terfilter
            'riwayatDokumen' => $riwayatDokumen,
            'dokumenTerbaru' => $dokumenTerbaru,
            'pembimbing1' => $pembimbing1,
            'pembimbing2' => $pembimbing2,
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
        ];
    }

    /**
     * [FUNGSI BARU] Mengatur jadwal untuk sesi bimbingan yang diajukan mahasiswa.
     * Fungsi ini akan meng-update record yang sudah ada, bukan membuat baru.
     */
    public function setJadwal(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // 1. Cari pengajuan bimbingan yang spesifik untuk dosen yang sedang login.
        $pengajuanBimbingan = $tugasAkhir->bimbinganTa()
            ->where('status_bimbingan', 'diajukan')
            ->where('dosen_id', $this->dosen->id) // <-- Hanya mencari record milik dosen ini
            ->first();

        // 2. Jika tidak ada pengajuan untuk dosen ini, lemparkan error.
        if (!$pengajuanBimbingan) {
            throw new \Exception('Tidak ada pengajuan bimbingan yang aktif untuk Anda atur jadwalnya.');
        }

        // 3. Update HANYA record bimbingan milik dosen ini.
        $pengajuanBimbingan->update([
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'jam_bimbingan'     => $data['jam_bimbingan'],
            'status_bimbingan'  => 'dijadwalkan',
        ]);
    }

    /**
     * [DIREVISI] Menyimpan catatan baru dari dosen ke sesi bimbingan yang relevan.
     */
    public function createCatatan(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        // Cari sesi bimbingan yang aktif (diajukan/dijadwalkan) untuk kedua dosen.
        $sesiBimbinganAktif = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
            ->get();

        if ($sesiBimbinganAktif->isEmpty()) {
            throw new \Exception('Tidak ada sesi bimbingan aktif untuk menambahkan catatan.');
        }

        // Simpan catatan ke setiap sesi aktif (untuk kedua dosen) agar log tetap sinkron.
        foreach ($sesiBimbinganAktif as $sesi) {
            $sesi->catatan()->create([
                'catatan'     => $data['catatan'],
                'author_type' => Dosen::class,
                'author_id'   => $this->dosen->id,
            ]);
        }
    }

    /**
     * Menyelesaikan sesi bimbingan dan memvalidasi dokumen TA terbaru.
     *
     * @param BimbinganTA $bimbingan
     * @return void
     * @throws \Exception
     */
    public function selesaikanSesi(BimbinganTA $bimbingan): void
    {
        // Pengecekan otorisasi
        if ($bimbingan->dosen_id !== $this->dosen->id) {
            throw new UnauthorizedException('Anda tidak berwenang menyelesaikan sesi bimbingan ini.');
        }

        // Pengecekan status bimbingan
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang berstatus "dijadwalkan" yang bisa diselesaikan.');
        }

        DB::transaction(function () use ($bimbingan) {
            // 1. Selesaikan sesi bimbingan
            $bimbingan->update(['status_bimbingan' => 'selesai']);

            $tugasAkhir = $bimbingan->tugasAkhir;
            $dokumenTerkait = $tugasAkhir->dokumenTa()->orderBy('version', 'desc')->first();

            if (!$dokumenTerkait) {
                Log::warning('Proses dihentikan: Tidak ditemukan dokumen TA untuk Tugas Akhir ID: ' . $tugasAkhir->id);
                return;
            }

            $updateData = [];
            $dosenId = $this->dosen->id;

            // =================================================================
            // PERUBAHAN UTAMA: Cek peran dosen dari tabel pivot 'peran_dosen_ta'
            // =================================================================
            $peranDosen = $tugasAkhir->peranDosenTa()
                ->where('dosen_id', $dosenId)
                ->first(); // Mengambil data peran dari relasi

            if ($peranDosen) {
                if ($peranDosen->peran === 'pembimbing1') {
                    $updateData['divalidasi_oleh_p1'] = $dosenId;
                } elseif ($peranDosen->peran === 'pembimbing2') {
                    $updateData['divalidasi_oleh_p2'] = $dosenId;
                }
            }
            // =================================================================

            // Lakukan update HANYA JIKA dosen adalah pembimbing yang sah
            if (!empty($updateData)) {
                $dokumenTerkait->update($updateData);
                $dokumenTerkait->refresh();

                // Ubah status validasi menjadi 'disetujui' HANYA JIKA KEDUA pembimbing sudah validasi
                if ($dokumenTerkait->divalidasi_oleh_p1 && $dokumenTerkait->divalidasi_oleh_p2) {
                    $dokumenTerkait->update(['status_validasi' => 'disetujui']);
                }
            } else {
                Log::warning("Dosen ID {$dosenId} mencoba menyelesaikan sesi untuk TA ID {$tugasAkhir->id} namun perannya tidak ditemukan di tabel peran_dosen_ta.");
            }
        });
    }

    /**
     * [DIREVISI] Membatalkan sesi bimbingan untuk kedua dosen.
     */
    public function cancelBimbingan(BimbinganTA $bimbingan): void
    {
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);

        $sesiTerkait = $bimbingan->tugasAkhir->bimbinganTa()
            ->where('sesi_ke', $bimbingan->sesi_ke)
            ->where('status_bimbingan', 'dijadwalkan');

        if ($sesiTerkait->count() === 0) {
            throw new \Exception('Tidak ada jadwal aktif untuk dibatalkan.');
        }

        $sesiTerkait->update(['status_bimbingan' => 'dibatalkan']);
    }

    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        if (!$tugasAkhir->peranDosenTa()->where('dosen_id', $this->dosen->id)->exists()) {
            throw new UnauthorizedException('Anda bukan pembimbing untuk tugas akhir ini.');
        }
    }
}
