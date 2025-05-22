<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JudulTA;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Pengumuman;

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
    public function showAccJudulTA()
    {
        return view('kaprodi.judulTA.AccJudulTA');
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

    /**
     * API endpoint to get jadwal sidang in JSON format
     */
    public function apiJadwal()
    {
        $jadwals = Jadwal::all();
        return response()->json([
            'status' => 'success',
            'data' => $jadwals
        ]);
    }

    /**
     * API endpoint to get judul TA in JSON format
     */
    public function apiJudulTA()
    {
        $judulTAs = JudulTA::all();
        return response()->json([
            'status' => 'success',
            'data' => $judulTAs
        ]);
    }

    /**
     * API endpoint to get nilai sidang in JSON format
     */
    public function apiNilaiSidang()
    {
        $nilais = Nilai::all();
        return response()->json([
            'status' => 'success',
            'data' => $nilais
        ]);
    }

    /**
     * API endpoint to get pengumuman in JSON format
     */
    public function apiPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return response()->json([
            'status' => 'success',
            'data' => $pengumumans
        ]);
    }
}
