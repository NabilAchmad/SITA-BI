<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TawaranTopik;
use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TopikController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $topikList = TawaranTopik::with('dosen')
            ->when($search, function ($query, $search) {
                return $query->where('judul_topik', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->whereDoesntHave('tugasAkhir', function ($query) {
                $query->where('mahasiswa_id', Auth::id());
            })
            ->available() // Hanya tampilkan yang kuotanya masih ada
            ->paginate(10)
            ->withQueryString(); // Tambahkan ini

        return view('mahasiswa.tugas-akhir.crud-ta.listTopik', compact('topikList'));
    }

    public function ambil($id)
    {
        $mahasiswaId = Auth::id();

        // Cek apakah mahasiswa sudah memiliki TA
        if (TugasAkhir::where('mahasiswa_id', $mahasiswaId)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki tugas akhir.');
        }

        $topik = TawaranTopik::findOrFail($id);

        // Cek kuota
        if (!$topik->isAvailable()) {
            return redirect()->back()->with('error', 'Kuota topik ini sudah penuh.');
        }

        // Buat tugas akhir
        TugasAkhir::create([
            'mahasiswa_id' => $mahasiswaId,
            'tawaran_topik_id' => $topik->id,
            'judul' => $topik->judul_topik,
            'status' => 'diajukan',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('tugas-akhir.index')
            ->with('success', 'Topik berhasil diambil.');
    }
}
