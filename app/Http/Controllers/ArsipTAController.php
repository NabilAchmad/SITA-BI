<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA;
use App\Models\NilaiSidang;
use App\Models\TugasAkhir;
use Illuminate\Pagination\LengthAwarePaginator;

class ArsipTAController extends Controller
{
    public function dashboard()
    {
        // Lulus Sidang Sempro (proposal yang statusnya lulus atau lulus_revisi)
        $lulusSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->whereIn('status', ['lulus', 'lulus_revisi'])
            ->count();

        // Lulus Sidang Akhir (akhir yang statusnya lulus atau lulus_revisi)
        $lulusAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->whereIn('status', ['lulus', 'lulus_revisi'])
            ->count();

        // Belum Lulus Sidang Akhir (akhir yang statusnya tidak_lulus)
        $belumLulusAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->where('status', 'tidak_lulus')
            ->count();

        // Rekapitulasi Nilai (jumlah total data nilai sidang)
        $rekapNilaiCount = NilaiSidang::count();

        // Dokumen Tugas Akhir (jumlah file yang diupload di tugas akhir)
        $dokumenTaCount = TugasAkhir::whereNotNull('file_path')->count();

        // Alumni Terdaftar (mahasiswa yang telah lulus sidang akhir dan status aktifnya 'alumni')
        $alumniCount = Mahasiswa::where('status', 'alumni')->count();

        return view('admin.arsip.dashboard.arsip', compact(
            'lulusSemproCount',
            'lulusAkhirCount',
            'belumLulusAkhirCount',
            'rekapNilaiCount',
            'dokumenTaCount',
            'alumniCount'
        ));
    }

    public function rekapNilai(Request $request) {}

    public function dokumenTa(Request $request) {}

    public function alumni(Request $request)
    {
        $query = Mahasiswa::with(['user', 'tugasAkhir', 'sidangAkhir'])
            ->whereHas('sidang', function ($q) {
                $q->where('status', 'lulus');
            });

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        $alumni = $query->paginate(10);

        return view('admin.arsip.daftar-alumni.read', compact('alumni'));
    }
}
