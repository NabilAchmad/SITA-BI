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

    // Jadwal Sidang Akhir
    public function showJadwal()
    {
        $jadwalAkhir = JadwalSidang::where('jenis_sidang', 'akhir')->paginate(10);
        return view('kajur.sidang.akhir.views.jadwal-akhir', compact('jadwalAkhir'));
    }

    // Jadwal Sidang Sempro
    public function showSidangSemproTerjadwal()
    {
        $jadwalSempro = JadwalSidang::where('jenis_sidang', 'sempro')->paginate(10);
        return view('kajur.sidang.sempro.jadwal-sempro', compact('jadwalSempro'));
    }

    // Pasca Sidang Sempro
    public function showPascaSidangSempro()
    {
        $pascaSempro = Sidang::where('jenis_sidang', 'sempro')->paginate(10);
        return view('kajur.sidang.sempro.pasca-sempro', compact('pascaSempro'));
    }

    // Pasca Sidang Akhir
    public function showPascaSidangAkhir()
    {
        $pascaAkhir = Sidang::where('jenis_sidang', 'akhir')->paginate(10);
        return view('kajur.sidang.akhir.pasca-akhir', compact('pascaAkhir'));
    }

    // Judul Tugas Akhir
    public function showJudulTA()
    {
        $judulTAs = JudulTA::where('status', 'diajukan')->get();
        return view('kajur.judulTA.AccJudulTA', compact('judulTAs'));
    }

    public function showAcc()
    {
        $judulAcc = JudulTA::where('status', 'disetujui')->get();
        return view('kajur.judulTA.readAcc', compact('judulAcc'));
    }

    public function showTolak()
    {
        $judulTolak = JudulTA::where('status', 'ditolak')->get();
        return view('kajur.judulTA.readTolak', compact('judulTolak'));
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        $nilais = Nilai::with([
            'mahasiswa',
            'tugasAkhir',
            'dosenPenguji'
        ])->paginate(15);

        return view('kajur.nilai.read', compact('nilais'));
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

    // Show Mahasiswa Sidang Dashboard Card
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

    /**
     * Menampilkan mahasiswa menunggu sidang sempro
     */
    public function showMahasiswaSidangSempro()
    {
        $mahasiswaMenunggu = Mahasiswa::whereDoesntHave('jadwalSidangSempro')->paginate(10);
        $mahasiswaTidakLulus = Mahasiswa::whereHas('tugasAkhir', function ($query) {
            $query->where('status', 'tidak lulus');
        })->paginate(10);
        return view('kajur.sidang.sempro.views.mhs-sidang', compact('mahasiswaMenunggu', 'mahasiswaTidakLulus'));
    }

    /**
     * Menampilkan mahasiswa menunggu sidang akhir
     */
    public function showMahasiswaMenungguAkhir()
    {
        $mahasiswaMenunggu = Mahasiswa::whereDoesntHave('jadwalSidangAkhir')->paginate(10);
        return view('kajur.sidang.akhir.views.mhs-menunggu-akhir', compact('mahasiswaMenunggu'));
    }
}
