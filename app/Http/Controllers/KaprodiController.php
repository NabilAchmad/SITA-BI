<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JudulTA;

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
        return view('kaprodi.jadwal.readJadwal');
    }

    // Acc Judul Tugas Akhir
    // compact('judulTA'));
    public function showAccJudulTA()
    {
        $judulTAs = JudulTA::all(); // Fetch all JudulTA records or adjust query as needed
        return view('kaprodi.judulTA.AccJudulTA', compact('judulTAs'));
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        return view('kaprodi.sidang.readSidang');
    }

    // Create Sidang
    public function createSidang()
    {
        return view('kaprodi.sidang.createSidang');
    }

    // Pengumuman
    public function showPengumuman()
    {
        return view('kaprodi.Pengumuman.pengumuman');
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
}
