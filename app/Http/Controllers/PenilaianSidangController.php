<?php

namespace App\Http\Controllers;

use App\Models\NilaiSidang;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianSidangController extends Controller
{
    // Tampilkan daftar semua sidang
    public function index()
    {
        $sidangs = Sidang::with('mahasiswa')->get();
        return view('admin.sidang.penilaian.index', compact('sidangs'));
    }

    // Tampilkan form penilaian untuk satu sidang
    public function form($sidang_id)
    {
        $sidang = Sidang::with('mahasiswa')->findOrFail($sidang_id);
        return view('admin.sidang.penilaian.form', compact('sidang'));
    }

    // Simpan nilai sidang
    public function simpan(Request $request, $sidang_id)
    {
        $request->validate([
            'aspek' => 'required|string',
            'komentar' => 'nullable|string',
            'skor' => 'required|numeric|min:0|max:100',
        ]);

        NilaiSidang::create([
            'sidang_id' => $sidang_id,
            'dosen_id' => Auth::id() ?? 4, // gunakan id dosen login, fallback ke 4 jika belum login
            'aspek' => $request->aspek,
            'komentar' => $request->komentar,
            'skor' => $request->skor,
        ]);

        return redirect()->route('admin.sidang.penilaian.index')->with('success', 'Nilai berhasil disimpan');
    }
}