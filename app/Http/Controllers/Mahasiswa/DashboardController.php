<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        // Ambil hanya pengumuman yang ditujukan ke mahasiswa atau ke semua pengguna
        $pengumuman = Pengumuman::whereIn('audiens', ['mahasiswa', 'all_users'])
            ->orderBy('tanggal_dibuat', 'desc')
            ->limit(5) // Ambil 5 pengumuman terbaru, bisa disesuaikan
            ->get();

        return view('mahasiswa.views.dashboard', compact('pengumuman'));
    }
}
