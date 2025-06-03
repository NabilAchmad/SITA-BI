<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevisiTA;

class TugasAkhirController extends Controller
{
    // Menampilkan dashboard
    public function dashboard()
    {
        $kemajuan = RevisiTA::latest()->get(); // atau sesuaikan dengan filter user/dosen
        return view('admin.ta.dashboard.dashboard', compact('kemajuan'));
    }

    // Menyimpan komentar revisi dari modal
    public function revisiStore(Request $request)
    {
        $request->validate([
            'komentar_revisi' => 'required|string|max:1000',
        ]);

        // Buat revisi baru
        RevisiTA::create([
            'tanggal' => now(),
            'deskripsi' => $request->komentar_revisi,
            'status' => 'Menunggu ACC',
        ]);

        return redirect()->route('ta.dashboard')->with('success', 'Komentar revisi berhasil dikirim.');
    }

    // Menyetujui (ACC) revisi tertentu
    public function acc($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status = 'ACC';
        $revisi->save();

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah di-ACC.');
    }

    // Menolak revisi tertentu
    public function tolak($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status = 'Ditolak';
        $revisi->save();

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah ditolak.');
    }
}
