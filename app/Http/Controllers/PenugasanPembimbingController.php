<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PeranDosenTa;
use Illuminate\Http\Request;

class PenugasanPembimbingController extends Controller
{
    // Tampilkan mahasiswa yang belum memiliki 2 pembimbing
    public function index(Request $request)
    {
        $query = Mahasiswa::with('user');

        if ($request->filled('prodi')) {
            $query->where('prodi', 'like', $request->prodi . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%$search%"))
                    ->orWhere('nim', 'like', "%$search%");
            });
        }

        $mahasiswa = $query->paginate(10);
        $dosen = Dosen::with('user')->get(); // Tambahkan ini!

        return view('admin.mahasiswa.views.assign-dospem', compact('mahasiswa', 'dosen'));
    }

    // Tampilkan form pilih pembimbing
    public function create($id)
    {
        $mahasiswa = Mahasiswa::with('user', 'tugasAkhir')->findOrFail($id);
        $dosen = Dosen::with('user')->get();

        return view('admin.mahasiswa.views.pilih-pembimbing', compact('mahasiswa', 'dosen'));
    }

    // Simpan pembimbing mahasiswa
    public function store(Request $request, $id)
    {
        $request->validate([
            'pembimbing' => 'required|array|size:2',
            'pembimbing.*' => 'exists:dosen,id',
        ]);

        $mahasiswa = Mahasiswa::with('tugasAkhir')->findOrFail($id);
        $tugasAkhirId = $mahasiswa->tugasAkhir->id;

        // Hapus pembimbing lama
        PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->delete();

        // Simpan pembimbing baru
        foreach ($request->pembimbing as $index => $dosenId) {
            PeranDosenTa::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $tugasAkhirId,
                'peran' => $index === 0 ? 'pembimbing1' : 'pembimbing2',
            ]);
        }

        return redirect()->route('penugasan-bimbingan.index')->with('success', 'Pembimbing berhasil ditetapkan.');
    }
}
