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
use App\Models\CatatanBimbingan;

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
     * [REVISI FINAL] Menangani upload file.
     * - Membuat DUA record bimbingan, satu untuk setiap pembimbing.
     * - Disesuaikan untuk model TugasAkhir yang menggunakan Accessor.
     */
    public function ajukanBimbinganDenganFile(TugasAkhir $tugasAkhir, UploadedFile $file, string $tipeDokumen, ?string $catatan = null): void
    {
        // Pastikan relasi dasar dimuat untuk efisiensi accessor (N+1 fix)
        $tugasAkhir->loadMissing('dosenPembimbing');

        if (optional($this->mahasiswa)->id !== $tugasAkhir->mahasiswa_id) {
            throw new UnauthorizedException('Anda tidak berwenang untuk tugas akhir ini.');
        }

        if ($tugasAkhir->bimbinganTa()->where('status_bimbingan', 'dijadwalkan')->exists()) {
            throw new \Exception('Gagal mengunggah file baru. Sesi bimbingan sudah dijadwalkan oleh dosen.');
        }

        // Mengambil objek Dosen melalui accessor
        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbingDua;

        if (!$pembimbing1) { // Cukup cek pembimbing 1 karena biasanya wajib ada
            throw new \Exception('Tidak dapat mengajukan bimbingan. Dosen Pembimbing 1 harus ditetapkan terlebih dahulu.');
        }

        $mahasiswaId = $tugasAkhir->mahasiswa_id;

        DB::transaction(function () use ($tugasAkhir, $file, $tipeDokumen, $catatan, $pembimbing1, $pembimbing2, $mahasiswaId) {

            // Cek apakah sudah ada sesi yang 'diajukan' untuk tugas akhir ini.
            $sesiAktif = $tugasAkhir->bimbinganTa()->where('status_bimbingan', 'diajukan')->get();

            if ($sesiAktif->isNotEmpty()) {
                // LOGIKA 1: Sesi 'diajukan' sudah ada. Gunakan sesi yang ada.
                // ✅ PERBAIKAN: Gunakan ->id karena $pembimbing1 adalah model Dosen
                $sesiBimbinganP1 = $sesiAktif->where('dosen_id', $pembimbing1->id)->first();
                $sesiBimbinganP2 = $pembimbing2 ? $sesiAktif->where('dosen_id', $pembimbing2->id)->first() : null;
            } else {
                // LOGIKA 2: Tidak ada sesi 'diajukan'. Buat sesi baru.
                $sesiKe = ($tugasAkhir->bimbinganTa()->where('status_bimbingan', 'selesai')->distinct('sesi_ke')->count()) + 1;

                $dataBimbingan = [
                    'sesi_ke'           => $sesiKe,
                    'status_bimbingan'  => 'diajukan',
                    'tanggal_bimbingan' => null,
                    'jam_bimbingan'     => null,
                ];

                // ✅ PERBAIKAN: Gunakan ->id dan tentukan peran secara eksplisit
                $sesiBimbinganP1 = $tugasAkhir->bimbinganTa()->create(array_merge($dataBimbingan, [
                    'dosen_id' => $pembimbing1->id,
                    'peran'    => 'pembimbing1'
                ]));

                if ($pembimbing2) {
                    $sesiBimbinganP2 = $tugasAkhir->bimbinganTa()->create(array_merge($dataBimbingan, [
                        'dosen_id' => $pembimbing2->id,
                        'peran'    => 'pembimbing2'
                    ]));
                }
            }

            // Pastikan $sesiBimbinganP1 ada sebelum melanjutkan
            if (!$sesiBimbinganP1) {
                throw new \Exception("Gagal menemukan atau membuat sesi bimbingan untuk Pembimbing 1.");
            }

            // Simpan file dan buat record dokumen yang BARU.
            $latestVersion = ($tugasAkhir->dokumenTa()->where('tipe_dokumen', $tipeDokumen)->max('version') ?? 0) + 1;
            $filePath = $file->store("dokumen_ta/{$tugasAkhir->id}/sesi_{$sesiBimbinganP1->sesi_ke}", 'public');

            $tugasAkhir->dokumenTa()->create([
                'tipe_dokumen'   => $tipeDokumen,
                'file_path'      => $filePath,
                'nama_file_asli' => $file->getClientOriginalName(),
                'version'        => $latestVersion,
            ]);

            // Simpan catatan baru jika ada.
            if (!empty($catatan)) {
                $dataCatatan = ['catatan' => $catatan, 'author_type' => Mahasiswa::class, 'author_id' => $mahasiswaId];
                $sesiBimbinganP1->catatan()->create($dataCatatan);
                if ($pembimbing2 && isset($sesiBimbinganP2)) {
                    $sesiBimbinganP2->catatan()->create($dataCatatan);
                }
            }
        });
    }

    // revisi bagian model
    public function createCatatanForMahasiswa(TugasAkhir $tugasAkhir, array $data)
    {
        if (optional($this->mahasiswa)->id !== $tugasAkhir->mahasiswa_id) {
            throw new UnauthorizedException('Anda tidak berwenang untuk tugas akhir ini.');
        }

        // Cari sesi bimbingan yang aktif untuk kedua dosen.
        $sesiBimbinganAktif = $tugasAkhir->bimbinganTa()->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])->get();

        if ($sesiBimbinganAktif->isEmpty()) {
            throw new \Exception('Tidak bisa mengirim catatan. Tidak ada sesi bimbingan yang sedang aktif.');
        }

        // Simpan catatan ke setiap sesi aktif agar log tetap sinkron.
        foreach ($sesiBimbinganAktif as $sesi) {
            $sesi->catatan()->create([
                'catatan'     => $data['catatan'],
                'author_type' => Mahasiswa::class,
                'author_id'   => $this->mahasiswa->id,
            ]);
        }
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
        // Guard clause jika tidak ada konteks mahasiswa
        if (!$this->mahasiswa) {
            return $this->getEmptyProgressData();
        }

        // 1. Ambil data utama menggunakan helper yang sudah dioptimalkan
        // Helper ini bertanggung jawab untuk eager loading semua relasi
        $tugasAkhir = $this->getActiveTugasAkhirForProgressPage();

        // Guard clause jika mahasiswa tidak memiliki tugas akhir aktif
        if (!$tugasAkhir) {
            return $this->getEmptyProgressData();
        }

        // 2. Gunakan koleksi yang SUDAH di-load (tidak ada query baru ke DB)
        $allBimbingan = $tugasAkhir->bimbinganTa;
        $riwayatDokumen = $tugasAkhir->dokumenTa;
        $pembimbing1 = $tugasAkhir->pembimbing_satu;
        $pembimbing2 = $tugasAkhir->pembimbing_dua;

        // 3. Olah data di memori untuk mendapatkan jadwal aktif
        $jadwalAktif = collect(); // Buat Laravel Collection kosong
        if ($pembimbing1) {
            $jadwalP1 = $allBimbingan
                ->where('dosen_id', $pembimbing1->id)
                ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
                ->sortByDesc('sesi_ke') // Ambil sesi terbaru
                ->first();

            if ($jadwalP1) {
                $jadwalAktif->push($jadwalP1);
            }
        }
        if ($pembimbing2) {
            $jadwalP2 = $allBimbingan
                ->where('dosen_id', $pembimbing2->id)
                ->whereIn('status_bimbingan', ['diajukan', 'dijadwalkan'])
                ->sortByDesc('sesi_ke')
                ->first();

            if ($jadwalP2) {
                $jadwalAktif->push($jadwalP2);
            }
        }

        // 4. Hitung jumlah bimbingan dari koleksi di memori (sudah diperbaiki)
        $bimbinganCountP1 = $pembimbing1 ? $allBimbingan->where('dosen_id', $pembimbing1->id)->where('status_bimbingan', 'selesai')->count() : 0;
        $bimbinganCountP2 = $pembimbing2 ? $allBimbingan->where('dosen_id', $pembimbing2->id)->where('status_bimbingan', 'selesai')->count() : 0;

        // 5. Kembalikan semua data yang sudah matang dan siap ditampilkan
        return [
            'tugasAkhir'       => $tugasAkhir,
            'riwayatDokumen'   => $riwayatDokumen,
            'dokumenTerbaru'   => $riwayatDokumen->last(),
            'catatanList'      => $tugasAkhir->catatanBimbingan, // Langsung dari relasi yang di-load
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
            'pembimbing1'      => $pembimbing1,
            'pembimbing2'      => $pembimbing2,
            'jadwalAktif'      => $jadwalAktif,
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
        if (!$this->mahasiswa) return null;

        return $this->mahasiswa->tugasAkhir()
            ->active()
            ->with([
                'dosenPembimbing.user',
                'dokumenTa',
                'bimbinganTa',
                // 'catatanBimbingan.author.user', // <-- PENYESUAIAN DI SINI
            ])
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
