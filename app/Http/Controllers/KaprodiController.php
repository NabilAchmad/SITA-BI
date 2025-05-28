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

class KaprodiController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    // Jadwal Sidang
    public function showJadwal()
    {
        $jadwals = jadwal::all();
        return view('kaprodi.jadwal.readJadwal', compact('jadwals'));
    }

    // Acc Judul Tugas Akhir
    public function showAccJudulTA()
    {
        $judulTAs = JudulTA::all();
        return view('kaprodi.judulTA.AccJudulTA', compact('judulTAs'));
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
        return view('kaprodi.sidang.createSidang');
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

        return redirect()->route('kaprodi.nilai.page')->with('success', 'Sidang berhasil dibuat.');
    }

    // Pengumuman
    public function showPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return view('kaprodi.Pengumuman.pengumuman', compact('pengumumans'));
    }

    // Approve JudulTA
    public function approveJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status = 'Disetujui';
        $judul->tanggal_acc = now();
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
        return view('kaprodi.sidang.dashboard.dashboard', compact('jadwalCount'));
    }

    public function showMahasiswaSidang()
    {
        $mahasiswaCount = Mahasiswa::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('mahasiswaCount'));
    }

    // Show Sidang Results following jadwal sidang from admin
    public function showSidangResults()
    {
        $jadwalSidangs = JadwalSidang::with(['sidang.nilai', 'sidang.tugasAkhir.mahasiswa'])->get();

        return view('kaprodi.sidang.read', compact('jadwalSidangs'));
    }
}
