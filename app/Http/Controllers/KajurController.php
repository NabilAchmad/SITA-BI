<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Sidang;
use App\Models\Jadwal;
use App\Models\JadwalSidang;
use App\Models\JudulTA;
use App\Models\Mahasiswa;
use App\Models\Nilai;

class KajurController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('kajur.dashboard');
    }

    // Jadwal Sidang
    public function showJadwal()
    {
        $jadwals = Jadwal::all();
        return view('kajur.jadwal.readJadwal', compact('jadwals'));
    }

    // Acc Judul Tugas Akhir
    public function showAccJudulTA()
    {
        $judulTAs = JudulTA::all();
        return view('kajur.judulTA.AccJudulTA', compact('judulTAs'));
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        $nilais = Nilai::all();
        return view('kajur.sidang.readSidang', compact('nilais'));
    }

    // Create Sidang
    public function createSidang()
    {
        return view('kajur.sidang.createSidang');
    }

    // Store Sidang (handle POST)
    public function storeSidang(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
        ]);

        Sidang::create($validated);

        return redirect()->route('kajur.nilai.page')->with('success', 'Sidang berhasil dibuat.');
    }

    // Pengumuman
    public function showPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return view('kajur.Pengumuman.pengumuman', compact('pengumumans'));
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

    // Sidang Dashboard
    public function showSidangDashboard()
    {
        $jadwalCount = JadwalSidang::count();
        return view('kajur.sidang.dashboard.dashboard', compact('jadwalCount'));
    }

    public function showMahasiswaSidang()
    {
        $mahasiswaCount = Mahasiswa::count();
        return view('kajur.sidang.dashboard.dashboard', compact('mahasiswaCount'));
    }

    // Show Sidang Results following jadwal sidang from admin
    public function showSidangResults()
    {
        $jadwalSidangs = JadwalSidang::with(['sidang.nilai', 'sidang.tugasAkhir.mahasiswa'])->get();

        return view('kajur.sidang.read', compact('jadwalSidangs'));
    }
}
