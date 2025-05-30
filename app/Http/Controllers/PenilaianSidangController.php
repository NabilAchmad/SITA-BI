<?php

namespace App\Http\Controllers;

use App\Models\NilaiSidang;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianSidangController extends Controller
{
    public function index()
    {
        $sidangs = Sidang::with('mahasiswa')->get();
        return view('penilaian.index', compact('sidangs'));
    }

    public function form($sidang_id)
    {
        $sidang = Sidang::with('mahasiswa')->findOrFail($sidang_id);
        return view('penilaian.form', compact('sidang'));
    }

    public function simpan(Request $request, $sidang_id)
    {
        $request->validate([
            'aspek' => 'required|string',
            'komentar' => 'nullable|string',
            'skor' => 'required|numeric|min:0|max:100',
        ]);

        NilaiSidang::create([
            'sidang_id' => $sidang_id,
            'dosen_id' => Auth::id(),
            'aspek' => $request->aspek,
            'komentar' => $request->komentar,
            'skor' => $request->skor,
        ]);

        return redirect()->route('penilaian.sidang.index')->with('success', 'Nilai berhasil disimpan');
    }
}