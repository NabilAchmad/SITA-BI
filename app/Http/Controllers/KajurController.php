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
        $mahasiswaCount = Mahasiswa::count();
        return view('kajur.views.dashboard', compact('mahasiswaCount'));
    }

    // Jadwal Sidang
    public function showJadwal()
    {
        $jadwals = Jadwal::all();
        return view('kajur.jadwal.readJadwal', compact('jadwals'));
    }

    // Judul Tugas Akhir
    public function showJudulTA()
    {
        $judulTAs = JudulTA::where('status', 'diajukan')->get();
        dd($judulTAs);
        return view('kajur.judulTA.AccJudulTA', compact('judulTAs'));
    }

    public function showAcc(){
        $judulAcc = JudulTA::where('status', 'disetujui')->get();
        dd($judulAcc);
        return view('kajur.judulTA.readAcc', compact('judulTAs'));
    }

    public function showTolak(){
        $judulTolak = JudulTA::where('status', 'ditolak')->get();
        dd($judulTolak);
        // Assuming you want to return a view with the rejected titles
        return view('kajur.judulTA.readTolak', compact('judulTolak'));
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        $nilais = Nilai::all();
        return view('kajur.sidang.readSidang', compact('nilais'));
    }

    // Pengumuman
    public function showPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return view('kajur.Pengumuman.pengumuman', compact('pengumumans'));
    }

    // Sidang Dashboard
    public function showSidangDashboard()
    {
        // Count mahasiswa waiting for Sidang Sempro scheduling
        $waitingSemproCount = Mahasiswa::whereDoesntHave('jadwalSidangSempro')->count();

        // Count mahasiswa waiting for Sidang Akhir scheduling
        $waitingAkhirCount = Mahasiswa::whereDoesntHave('jadwalSidangAkhir')->count();

        // Count scheduled Sidang Sempro
        $scheduledSemproCount = JadwalSidang::where('jenis_sidang', 'sempro')->count();

        // Count scheduled Sidang Akhir
        $scheduledAkhirCount = JadwalSidang::where('jenis_sidang', 'akhir')->count();

        // Count Pasca Sidang Sempro
        $pascaSemproCount = Sidang::where('jenis_sidang', 'sempro')->count();

        // Count Pasca Sidang Akhir
        $pascaAkhirCount = Sidang::where('jenis_sidang', 'akhir')->count();

        return view('kajur.sidang.dashboard.dashboard', compact(
            'waitingSemproCount',
            'waitingAkhirCount',
            'scheduledSemproCount',
            'scheduledAkhirCount',
            'pascaSemproCount',
            'pascaAkhirCount'
        ));
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
