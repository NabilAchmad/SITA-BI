<?php

namespace App\Http\Controllers\Dosen\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    // Dosen/ValidasiJudulController.php
    public function index(Request $request)
    {
        $query = TugasAkhir::with(['mahasiswa.user'])
            ->where('status', 'diajukan');

        if ($request->prodi) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi', strtolower($request->prodi)));
        }

        if ($request->search) {
            $query->whereHas(
                'mahasiswa.user',
                fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            );
        }

        $tugasAkhir = $query->latest()->get();
        return view('dosen.kaprodi.validasi.index', compact('tugasAkhir'));
    }

    public function detail($id)
    {
        $ta = TugasAkhir::with('mahasiswa.user')->findOrFail($id);
        $judul = strtolower($ta->judul);

        // Cari judul serupa
        $similar = TugasAkhir::where('id', '!=', $id)
            ->where('status', '!=', 'ditolak')
            ->pluck('judul')
            ->filter(function ($item) use ($judul) {
                similar_text(strtolower($item), $judul, $percent);
                return $percent > 60;
            });

        // Ambil score tertinggi dan simpan (opsional)
        $maxScore = 0;
        foreach ($similar as $item) {
            similar_text(strtolower($item), $judul, $score);
            $maxScore = max($maxScore, $score);
        }

        $ta->similarity_score = $maxScore;
        $ta->terakhir_dicek = now();
        $ta->save();

        return response()->json([
            'nama' => $ta->mahasiswa->user->name,
            'nim' => $ta->mahasiswa->nim,
            'prodi' => strtoupper($ta->mahasiswa->prodi) === 'D3' ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris',
            'judul' => $ta->judul,
            'similar' => $similar->values(),
        ]);
    }

    public function validasi($id)
    {
        $ta = TugasAkhir::findOrFail($id);
        $ta->status = 'disetujui';
        $ta->save();

        return back()->with('success', 'Judul disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['komentar' => 'required|string']);

        $ta = TugasAkhir::findOrFail($id);
        $ta->status = 'ditolak';
        $ta->alasan_penolakan = $request->komentar;
        $ta->save();

        return back()->with('success', 'Judul ditolak.');
    }
}
