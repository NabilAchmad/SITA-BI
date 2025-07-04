<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\HistoryTopikMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTa;
use App\Models\TawaranTopik;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TopikPengajuanService
{
    /**
     * Mengambil semua tawaran topik yang masih tersedia untuk mahasiswa.
     */
    public function getAvailableTopics()
    {
        return TawaranTopik::where('kuota', '>', 0)->with('dosen.user')->latest()->paginate(10);
    }

    /**
     * PENAMBAHAN: Mengambil daftar pengajuan mahasiswa untuk topik milik dosen tertentu, dengan filter.
     */
    public function getApplicationsForDosen(Dosen $dosen, Request $request)
    {
        $query = HistoryTopikMahasiswa::query()
            ->whereHas('tawaranTopik', function ($q) use ($dosen) {
                $q->where('user_id', $dosen->user_id);
            })
            ->with(['mahasiswa.user', 'tawaranTopik']);

        // Terapkan filter berdasarkan prodi jika ada
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        // Tampilkan hanya pengajuan yang masih menunggu keputusan
        $query->where('status', 'diajukan');

        // Gunakan nama parameter 'mahasiswa_page' untuk paginasi tab ini agar tidak konflik
        return $query->latest()->paginate(10, ['*'], 'mahasiswa_page');
    }

    /**
     * Logika untuk mahasiswa mengajukan topik dari dosen.
     */
    public function applyForTopic(TawaranTopik $topik, Mahasiswa $mahasiswa): HistoryTopikMahasiswa
    {
        // Gunakan DB::transaction untuk memastikan semua operasi berhasil atau gagal bersamaan.
        return DB::transaction(function () use ($topik, $mahasiswa) {

            // =========================================================================
            // PERBAIKAN: Kunci baris topik untuk update (Pessimistic Locking)
            // Ini akan mencegah race condition saat beberapa mahasiswa mencoba
            // mengambil topik yang sama secara bersamaan.
            // =========================================================================
            $lockedTopik = TawaranTopik::where('id', $topik->id)->lockForUpdate()->firstOrFail();

            // Pengecekan awal sekarang menggunakan data yang sudah dikunci dan pasti fresh.
            if ($mahasiswa->tugasAkhir()->where('status', '!=', 'dibatalkan')->exists()) {
                throw new \Exception('Anda sudah memiliki tugas akhir yang aktif atau sedang dalam proses.');
            }
            // Gunakan $lockedTopik untuk pengecekan kuota
            if ($lockedTopik->kuota <= 0) {
                throw new \Exception('Topik ini sudah tidak tersedia atau kuota sudah penuh.');
            }
            if (HistoryTopikMahasiswa::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diajukan')->exists()) {
                throw new \Exception('Anda sudah memiliki pengajuan topik lain yang sedang diproses.');
            }

            // 1. Buat catatan histori terlebih dahulu
            $history = HistoryTopikMahasiswa::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tawaran_topik_id' => $lockedTopik->id,
                'status' => 'diajukan',
            ]);

            // 2. Jika histori berhasil dibuat, baru kurangi kuota menggunakan data yang terkunci
            $lockedTopik->decrement('kuota');

            return $history;
        });
    }

    /**
     * Logika untuk dosen menyetujui pengajuan topik dari mahasiswa.
     */
    public function approveApplication(HistoryTopikMahasiswa $application): TugasAkhir
    {
        return DB::transaction(function () use ($application) {
            // Kunci baris aplikasi untuk mencegah pemrosesan ganda
            $application = HistoryTopikMahasiswa::lockForUpdate()->findOrFail($application->id);

            // Pastikan aplikasi masih dalam status 'diajukan'
            if ($application->status !== 'diajukan') {
                throw new \Exception('Pengajuan ini sudah diproses sebelumnya.');
            }

            $topik = $application->tawaranTopik;
            $mahasiswa = $application->mahasiswa;

            // =========================================================================
            // PERBAIKAN: Hapus pengecekan kuota dan decrement ganda.
            // Kuota sudah diamankan pada saat mahasiswa mengajukan.
            // =========================================================================

            // 1. Ubah status pengajuan
            $application->update(['status' => 'disetujui']);

            // 2. Buat entri Tugas Akhir baru
            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $topik->judul_topik,
                'abstrak' => $topik->deskripsi,
                'status' => TugasAkhir::STATUS_DISETUJUI, // Atau status awal lainnya
                'tanggal_pengajuan' => now(),
                'tawaran_topik_id' => $topik->id,
            ]);

            // 3. Tetapkan dosen sebagai Pembimbing 1
            PeranDosenTa::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_id' => $topik->dosen->id, // Pastikan relasi $topik->dosen ada dan benar
                'peran' => PeranDosenTa::PERAN_PEMBIMBING_1,
            ]);

            return $tugasAkhir;
        });
    }

    /**
     * Logika untuk dosen menolak pengajuan topik.
     */
    public function rejectApplication(HistoryTopikMahasiswa $application): bool
    {
        return DB::transaction(function () use ($application) {
            // Kunci baris aplikasi dan topik untuk update
            $application = HistoryTopikMahasiswa::lockForUpdate()->findOrFail($application->id);
            $topik = TawaranTopik::lockForUpdate()->findOrFail($application->tawaran_topik_id);

            if ($application->status !== 'diajukan') {
                throw new \Exception('Pengajuan ini sudah diproses sebelumnya.');
            }

            // 1. Ubah status pengajuan menjadi ditolak
            $application->update(['status' => 'ditolak']);

            // =========================================================================
            // PERBAIKAN: Kembalikan kuota yang sebelumnya diambil oleh mahasiswa.
            // =========================================================================
            $topik->increment('kuota');

            return true;
        });
    }
}
