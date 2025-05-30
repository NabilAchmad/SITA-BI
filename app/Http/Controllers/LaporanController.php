<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\Sidang;
use App\Models\TugasAkhir;

class LaporanController extends Controller
{
    public function show()
    {
        // Mahasiswa
        $mahasiswaPerProdi = Mahasiswa::select('prodi', DB::raw('COUNT(*) as total'))->groupBy('prodi')->get();
        $mahasiswaPerAngkatan = Mahasiswa::select('angkatan', DB::raw('COUNT(*) as total'))->groupBy('angkatan')->get();
        $mahasiswaPerStatus = Mahasiswa::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();

        // Alumni
        $totalAlumni = Mahasiswa::where('status', 'alumni')->count();

        // Sidang
        $sidangStatistik = Sidang::select('jenis_sidang', 'status', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_sidang', 'status')->get();

        // Bimbingan TA
        $bimbinganPerDosen = DB::table('bimbingan_ta')
            ->join('dosen', 'bimbingan_ta.dosen_id', '=', 'dosen.id')
            ->select('dosen.id', 'dosen_id', DB::raw('COUNT(*) as total'))
            ->groupBy('dosen.id', 'dosen_id')->get();

        // Dokumen TA
        $dokumenStatistik = DB::table('dokumen_ta')
            ->select('tipe_dokumen', 'status_validasi', DB::raw('COUNT(*) as total'))
            ->groupBy('tipe_dokumen', 'status_validasi')->get();

        // Revisi TA
        $totalRevisiTA = DB::table('revisi_ta')->count();
        $revisiStatus = DB::table('revisi_ta')
            ->select('status_revisi', DB::raw('COUNT(*) as total'))
            ->groupBy('status_revisi')->get();

        // Similarity (plagiarism)
        $similarityStat = TugasAkhir::select(
            DB::raw('CASE 
                        WHEN similarity_score >= 50 THEN ">= 50%"
                        WHEN similarity_score >= 30 THEN "30-49%"
                        WHEN similarity_score >= 10 THEN "10-29%"
                        ELSE "< 10%" END as kategori'),
            DB::raw('COUNT(*) as total')
        )->groupBy('kategori')->get();

        // Dosen sebagai penguji
        $pengujiStat = DB::table('peran_dosen_ta')
            ->where('peran', 'like', 'penguji%')
            ->select('dosen_id', DB::raw('COUNT(*) as total'))
            ->groupBy('dosen_id')->get();

        // Review Dokumen
        $reviewDokumenStat = DB::table('review_dokumen_ta')
            ->select('status_review', DB::raw('COUNT(*) as total'))
            ->groupBy('status_review')->get();

        return view('admin.laporan.views.lihatLaporanStatistik', compact(
            'mahasiswaPerProdi',
            'mahasiswaPerAngkatan',
            'mahasiswaPerStatus',
            'totalAlumni',
            'sidangStatistik',
            'bimbinganPerDosen',
            'dokumenStatistik',
            'totalRevisiTA',
            'revisiStatus',
            'similarityStat',
            'pengujiStat',
            'reviewDokumenStat'
        ));
    }
}
