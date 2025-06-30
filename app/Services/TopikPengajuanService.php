<?php

namespace App\Services;

use App\Models\HistoryTopikMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTa;
use App\Models\TawaranTopik;
use App\Models\TugasAkhir;
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
     * Logika untuk mahasiswa mengajukan topik dari dosen.
     */
    public function applyForTopic(TawaranTopik $topik, Mahasiswa $mahasiswa): HistoryTopikMahasiswa
    {
        // 1. Validasi
        if ($mahasiswa->tugasAkhir()->active()->exists()) {
            throw new \Exception('Anda sudah memiliki tugas akhir yang aktif.');
        }
        if ($topik->kuota <= 0) {
            throw new \Exception('Topik ini sudah tidak tersedia atau kuota sudah penuh.');
        }
        if (HistoryTopikMahasiswa::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diajukan')->exists()) {
            throw new \Exception('Anda sudah memiliki pengajuan topik lain yang sedang diproses.');
        }

        // 2. Buat catatan histori pengajuan
        return HistoryTopikMahasiswa::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tawaran_topik_id' => $topik->id,
            'status' => 'diajukan', // Status awal: menunggu persetujuan dosen
        ]);
    }

    /**
     * Logika untuk dosen menyetujui pengajuan topik dari mahasiswa.
     */
    public function approveApplication(HistoryTopikMahasiswa $application): TugasAkhir
    {
        return DB::transaction(function () use ($application) {
            $topik = $application->tawaranTopik;
            $mahasiswa = $application->mahasiswa;

            if ($topik->kuota <= 0) {
                throw new \Exception('Kuota untuk topik ini sudah habis.');
            }

            // 1. Update status pengajuan
            $application->update(['status' => 'disetujui']);

            // 2. Kurangi kuota topik
            $topik->decrement('kuota');

            // 3. Buat data Tugas Akhir baru
            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $topik->judul_topik,
                'abstrak' => $topik->deskripsi,
                'status' => TugasAkhir::STATUS_DISETUJUI,
                'tanggal_pengajuan' => now(),
                'tawaran_topik_id' => $topik->id,
            ]);

            // 4. Jadikan dosen pemilik topik sebagai Pembimbing 1
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
        return $application->update(['status' => 'ditolak']);
    }
}
