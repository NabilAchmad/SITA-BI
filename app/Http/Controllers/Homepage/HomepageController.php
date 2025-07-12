<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::whereIn('audiens', ['all_users', 'guest'])
            ->orderByDesc('tanggal_dibuat')
            ->take(10)
            ->get();

        // Fetch jadwal sidang akhir scheduled by Admin
        $jadwalSidangAkhir = JadwalSidang::with('sidang', 'ruangan')
            ->whereHas('sidang', function ($query) {
                $query->where('jenis_sidang', 'akhir');
            })
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        return view('home.homepage', compact('pengumuman', 'jadwalSidangAkhir'));
    }
}
