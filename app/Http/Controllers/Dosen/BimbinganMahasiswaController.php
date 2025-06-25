<?php

namespace App\Http\Controllers\Dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BimbinganTA;
use Illuminate\Support\Facades\Auth;
use App\Models\PeranDosenTA;
use App\Models\TugasAkhir;

class BimbinganMahasiswaController extends Controller
{

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Akun ini belum terhubung dengan entitas dosen.');
        }

        // Query utama
        $query = PeranDosenTA::with(['tugasAkhir.mahasiswa.user'])
            ->where('dosen_id', $dosen->id)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2']);

        // Filter Prodi
        if ($request->filled('prodi')) {
            $query->whereHas('tugasAkhir.mahasiswa', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        // Filter Nama Mahasiswa
        if ($request->filled('search')) {
            $query->whereHas('tugasAkhir.mahasiswa.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $mahasiswaList = $query->latest()->get();

        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    public function showDetail($id)
    {
        $ta = TugasAkhir::with(['mahasiswa.user'])
            ->where('mahasiswa_id', $id)
            ->firstOrFail();
        $bimbinganList = $ta->bimbingan()->latest()->get(); // relasi bimbingan
        $revisiList = $ta->revisi()->latest()->get();       // relasi revisi

        return view('dosen.bimbingan.detail-bimbingan.detail', [
            'mahasiswa' => $ta->mahasiswa,
            'tugasAkhir' => $ta,
            'bimbinganList' => $bimbinganList,
            'revisiList' => $revisiList,
        ]);
    }

    // Tambahkan method tolak agar tidak error
    public function tolak(Request $request)
    {
        // Validasi input
        $request->validate([
            'bimbingan_id' => 'required',
            'komentar_penolakan' => 'required|string|max:1000',
        ]);

        return redirect()->back()->with('success', 'Bimbingan berhasil ditolak.');
    }
}
