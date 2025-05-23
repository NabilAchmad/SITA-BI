<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\PeranDosenTA;
use App\Models\Log;

class AdminController extends Controller
{

    public function index()
    {
        $totalDosen = Dosen::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalPengumuman = Pengumuman::count();
        $riwayatTA = TugasAkhir::latest()->take(5)->get();
        $pengumumans = Pengumuman::with('pembuat')->orderBy('created_at', 'desc')->get();
        $logs = Log::with('user') // Jika ada relasi ke user
            ->latest()
            ->take(50)
            ->get();

        // Dosen yang sedang online (menggunakan cache-based online detection)
        $dosenAktif = Dosen::all()->filter(fn($d) => $d->isOnline());

        // Dosen Pembimbing (distinct dosen_id dari peran_dosen_ta dengan peran seperti pembimbing1, pembimbing2)
        $totalPembimbing = PeranDosenTA::whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->distinct('dosen_id')
            ->count('dosen_id');

        // Dosen Penguji (distinct dosen_id dari peran_dosen_ta dengan peran penguji1–penguji4)
        $totalPenguji = PeranDosenTA::whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->distinct('dosen_id')
            ->count('dosen_id');

        // Mahasiswa aktif = yang punya tugas akhir dan belum dibatalkan
        $mahasiswaAktif = TugasAkhir::whereNull('alasan_pembatalan')
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');

        return view('admin.views.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalPengumuman',
            'riwayatTA',
            'pengumumans',
            'dosenAktif',
            'totalPembimbing',
            'totalPenguji',
            'mahasiswaAktif',
            'logs'
        ));
    }
}
