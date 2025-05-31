<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RevisiTA;
use App\Models\ProgressTA;
use App\Models\PembatalanTA;

class TugasAkhirController extends Controller
{
    public function index()
{
    // Bisa ambil data apapun yang ingin ditampilkan di halaman utama TA
    return view('ta.index');
}
    // 1. Lihat laporan kemajuan tugas akhir
    public function lihatKemajuan()
    {
        $userId = Auth::id();
        $kemajuan = ProgressTA::where('user_id', $userId)->latest()->get();

        return view('ta.kemajuan.index', compact('kemajuan'));
    }

    // 2. Lihat dan unggah revisi tugas akhir
    public function revisi()
    {
        $userId = Auth::id();
        $revisi = RevisiTA::where('user_id', $userId)->latest()->get();

        return view('ta.revisi.index', compact('revisi'));
    }

    public function uploadRevisi(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $path = $request->file('file')->store('revisi_ta');

        RevisiTA::create([
            'user_id' => Auth::id(),
            'file' => $path,
            'uploaded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Revisi berhasil diunggah.');
    }

    // 3. Melihat riwayat pembatalan tugas akhir
    public function pembatalan()
    {
        $userId = Auth::id();
        $riwayatPembatalan = PembatalanTA::where('user_id', $userId)->latest()->get();

        return view('ta.pembatalan.index', compact('riwayatPembatalan'));
    }
}
