<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PeranDosenTa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenugasanPembimbingController extends Controller
{
    public function indexPembimbing(Request $request)
    {
        $mahasiswa = Mahasiswa::with(['user', 'tugasAkhir.peranDosenTA.dosen.user'])
            ->whereHas('tugasAkhir.peranDosenTA', function ($q) {
                $q->whereIn('peran', ['pembimbing1', 'pembimbing2']);
            }, '>=', 2)  // Pastikan minimal 2 pembimbing
            ->when($request->filled('prodi'), function ($query) use ($request) {
                $query->where('prodi', 'like', $request->prodi . '%');
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nim', 'like', "%$search%")
                        ->orWhereHas('user', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%$search%");
                        });
                });
            })
            ->orderBy('nim')
            ->paginate(10);

        return view('admin.mahasiswa.views.list-mhs', compact('mahasiswa'));
    }

    // Tampilkan mahasiswa yang belum memiliki 2 pembimbing
    public function indexWithOutPembimbing(Request $request)
    {
        $query = Mahasiswa::with(['user', 'tugasAkhir'])
            ->withCount(['peranDosenTA as pembimbing_count' => function ($q) {
                $q->where('peran', 'like', 'pembimbing%');
            }])
            ->having('pembimbing_count', '<', 2);

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
        $dosen = Dosen::with('user')->get();

        return view('admin.mahasiswa.views.assign-dospem', compact('mahasiswa', 'dosen'));
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
