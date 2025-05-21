<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JudulTA;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Sidang;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Fetch mahasiswa related data
        $jadwal = Jadwal::where('mahasiswa_id', $user->id)->get();
        $judulTA = JudulTA::where('mahasiswa_id', $user->id)->first();
        $nilai = Nilai::where('mahasiswa_id', $user->id)->get();
        $pengumuman = Pengumuman::orderBy('tanggal', 'desc')->limit(5)->get();
        $sidang = Sidang::where('mahasiswa_id', $user->id)->first();

        return view('mahasiswa.dashboard', compact('jadwal', 'judulTA', 'nilai', 'pengumuman', 'sidang'));
    }
}
