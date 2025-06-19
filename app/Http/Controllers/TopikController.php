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
    private function assumedMahasiswaId()
    {
        // Ganti ini sesuai ID mahasiswa yang ada di tabel users
        return 1;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswaId = $this->assumedMahasiswaId();

        // Cek apakah mahasiswa sudah punya TA dengan status tertentu
        $mahasiswaSudahPunyaTA = TugasAkhir::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['diajukan', 'revisi', 'disetujui', 'lulus_tanpa_revisi'])
            ->exists();

        $topikList = TawaranTopik::with('dosen')
            ->when($search, function ($query, $search) {
                return $query->where('judul_topik', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->whereDoesntHave('tugasAkhir', function ($query) use ($mahasiswaId) {
                $query->where('mahasiswa_id', $mahasiswaId);
            })
            ->available()
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.tugas-akhir.crud-ta.listTopik', compact('topikList', 'mahasiswaSudahPunyaTA'));
    }

    public function ambil($id)
    {
        $mahasiswaId = $this->assumedMahasiswaId();

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
