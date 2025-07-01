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
        // ... (kode tidak berubah) ...
        if ($mahasiswa->tugasAkhir()->active()->exists()) {
            throw new \Exception('Anda sudah memiliki tugas akhir yang aktif.');
        }
        if ($topik->kuota <= 0) {
            throw new \Exception('Topik ini sudah tidak tersedia atau kuota sudah penuh.');
        }
        if (HistoryTopikMahasiswa::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diajukan')->exists()) {
            throw new \Exception('Anda sudah memiliki pengajuan topik lain yang sedang diproses.');
        }
        return HistoryTopikMahasiswa::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tawaran_topik_id' => $topik->id,
            'status' => 'diajukan',
        ]);
    }

    /**
     * Logika untuk dosen menyetujui pengajuan topik dari mahasiswa.
     */
    public function approveApplication(HistoryTopikMahasiswa $application): TugasAkhir
    {
        // ... (kode tidak berubah) ...
        return DB::transaction(function () use ($application) {
            $topik = $application->tawaranTopik;
            $mahasiswa = $application->mahasiswa;

            if ($topik->kuota <= 0) {
                throw new \Exception('Kuota untuk topik ini sudah habis.');
            }

            $application->update(['status' => 'disetujui']);
            $topik->decrement('kuota');

            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $topik->judul_topik,
                'abstrak' => $topik->deskripsi,
                'status' => TugasAkhir::STATUS_DISETUJUI,
                'tanggal_pengajuan' => now(),
                'tawaran_topik_id' => $topik->id,
            ]);

            PeranDosenTa::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_id' => $topik->dosen->id,
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
        // ... (kode tidak berubah) ...
        return $application->update(['status' => 'ditolak']);
    }
}
