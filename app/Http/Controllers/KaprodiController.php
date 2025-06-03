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
    /**
     * Dashboard utama Kaprodi
     */
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    /**
     * Menampilkan seluruh jadwal sidang
     */
    public function showJadwal()
    {
        $jadwals = Jadwal::all();
        return view('kaprodi.jadwal.readJadwal', compact('jadwals'));
    }

    /**
     * Menampilkan daftar Judul TA yang masih berstatus "diajukan" (belum di‑ACC)
     */
    public function showAccJudulTA()
    {
        $judulTAs = JudulTA::where('status', 'diajukan')->get();
        return view('kaprodi.judulTA.AccJudulTA', compact('judulTAs'));
    }

    /**
     * Daftar Nilai Sidang Akhir (read‑only untuk Kaprodi)
     */
    public function sidangAkhir()
    {
        $nilais = Nilai::with([
            'mahasiswa',        // relasi ke tabel mahasiswa
            'tugasAkhir',       // relasi ke tugas_akhir (jika ada)
            'dosenPenguji'      // relasi ke dosen penguji (jika ada)
        ])->get();

        return view('kaprodi.nilai.read', compact('nilais'));
    }

    /**
     * Menyimpan data sidang baru (jika Kaprodi membuat sidang manual)
     */
    public function storeSidang(Request $request)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai'   => 'nullable|numeric',
            'status'  => 'nullable|string|max:50',
        ]);

        Sidang::create($validated);

        return redirect()
            ->route('kaprodi.nilai.page')
            ->with('success', 'Sidang berhasil dibuat.');
    }

    /**
     * Menampilkan seluruh pengumuman
     */
    public function showPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return view('kaprodi.Pengumuman.pengumuman', compact('pengumumans'));
    }

    /**
     * ACC Judul TA
     */
    public function approveJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status       = 'Disetujui';
        $judul->tanggal_acc  = now();
        $judul->save();

        return response()->json(['message' => 'Judul telah disetujui']);
    }

    /**
     * Menolak Judul TA
     */
    public function rejectJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status = 'Ditolak';
        $judul->save();

        return response()->json(['message' => 'Judul telah ditolak']);
    }

    /**
     * Dashboard ringkasan Sidang
     */
    public function showSidangDashboard()
    {
        $jadwalCount = JadwalSidang::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('jadwalCount'));
    }

    /**
     * Statistik jumlah mahasiswa yang akan sidang
     */
    public function showMahasiswaSidang()
    {
        $mahasiswaCount = Mahasiswa::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('mahasiswaCount'));
    }

    /**
     * Menampilkan hasil sidang sesuai jadwal yang dibuat admin
     */
    public function showSidangResults()
    {
        $jadwalSidangs = JadwalSidang::with([
            'sidang.nilai',
            'sidang.tugasAkhir.mahasiswa'
        ])->get();

        return view('kaprodi.sidang.read', compact('jadwalSidangs'));
    }
}
