<?php

namespace App\Http\Controllers;


use App\Models\JadwalSidang;
use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Mahasiswa;

class KaprodiController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    // Sidang Dashboard
    public function showSidangDashboard()
    {
        $jadwalCount = JadwalSidang::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('jadwalCount'));
    }

    public function showMahasiswaSidang()
    {
        $mahasiswaCount = Mahasiswa::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('mahasiswaCount'));
    }

    // Jadwal Sidang
    public function showJadwal()
    {
        $jadwalSidangs = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa',
            'sidang.peranDosenTa' => function ($query) {
                $query->whereIn('peran', ['penguji1', 'penguji2']);
            },
            'ruangan'
        ])->get();

        return view('kaprodi.jadwal.readJadwal', compact('jadwalSidangs'));
    }

    // Acc Judul Tugas Akhir
    // compact('judulTA'));
    public function showAccJudulTA()
    {
        $judulTAs = JudulTA::all(); // Fetch all JudulTA records or adjust query as needed
        return view('kaprodi.judulTA.AccJudulTA', compact('judulTAs'));
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        $jadwalSidangs = JadwalSidang::with(['sidang.nilai', 'sidang.tugasAkhir.mahasiswa'])->get();

        return view('kaprodi.sidang.read', compact('jadwalSidangs'));
    }

    // Show Sidang Results following jadwal sidang from admin
    public function showSidangResults()
    {
        $jadwalSidangs = JadwalSidang::with(['sidang.nilai', 'sidang.tugasAkhir.mahasiswa'])->get();

        return view('kaprodi.sidang.read', compact('jadwalSidangs'));
    }

    // Create Sidang
    public function createSidang()
    {
        return view('kaprodi.sidang.createSidang');
    }

    // Pengumuman
    public function showPengumuman()
    {
        return view('kaprodi.Pengumuman.pengumuman');
    }

    // Approve JudulTA
    public function approveJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status = 'Disetujui';
        $judul->save();

        return response()->json(['message' => 'Judul telah di-ACC']);
    }

    // Reject JudulTA
    public function rejectJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status = 'Ditolak';
        $judul->save();

        return response()->json(['message' => 'Judul telah ditolak']);
    }
}
