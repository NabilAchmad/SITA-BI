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
        return 18;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswaId = $this->assumedMahasiswaId();

        // Cek apakah mahasiswa sudah punya TA aktif (selain ditolak/dibatalkan)
        $mahasiswaSudahPunyaTA = TugasAkhir::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', [
                'diajukan',
                'revisi',
                'disetujui',
                'lulus_tanpa_revisi',
                'lulus_dengan_revisi',
                'draft',
                'menunggu_pembatalan'
            ])
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

        // Cek apakah mahasiswa sudah memiliki TA aktif
        if (TugasAkhir::where('mahasiswa_id', $mahasiswaId)->whereNull('deleted_at')->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki tugas akhir.');
        }

        $topik = TawaranTopik::with('dosen')->findOrFail($id);

        // Cek kuota
        if (!$topik->isAvailable()) {
            return redirect()->back()->with('error', 'Kuota topik ini sudah penuh.');
        }

        // Buat tugas akhir baru
        $ta = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswaId,
            'tawaran_topik_id' => $topik->id,
            'judul' => $topik->judul_topik,
            'abstrak' => $topik->deskripsi ?? '-',
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Tambahkan dosen sebagai pembimbing1 otomatis
        \App\Models\PeranDosenTA::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $topik->dosen_id,
            'peran' => 'pembimbing1'
        ]);

        return redirect()->route('tugas-akhir.index')
            ->with('success', 'Topik berhasil diambil dan pembimbing ditetapkan.');
    }
}
