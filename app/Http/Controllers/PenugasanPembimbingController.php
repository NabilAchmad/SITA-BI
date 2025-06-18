<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PeranDosenTa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenugasanPembimbingController extends Controller
{
    public function indexPembimbing(Request $request)
    {
        $mahasiswa = Mahasiswa::with(['user', 'tugasAkhir.peranDosenTA.dosen.user'])
            ->whereHas('tugasAkhir.peranDosenTA', function ($q) {
                $q->whereIn('peran', ['pembimbing1', 'pembimbing2']);
            }, '>=', 2)
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

        $dosen = Dosen::with('user')->get(); // <-- ini ditambahkan

        return view('admin.mahasiswa.views.list-mhs', compact('mahasiswa', 'dosen'));
    }

    public function indexWithOutPembimbing(Request $request)
    {
        $query = Mahasiswa::with(['user', 'tugasAkhir'])
            ->withCount([
                'peranDosenTA as pembimbing_count' => function ($q) {
                    $q->where('peran', 'like', 'pembimbing%');
                }
            ])
            ->having('pembimbing_count', '<', 1)
            ->whereHas('tugasAkhir', function ($q) {
                $q->where('status', 'disetujui');
            });

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

    public function store(Request $request, $mahasiswaId)
    {
        $request->validate([
            'pembimbing' => 'required|array|size:2',
            'pembimbing.*' => 'exists:dosen,id',
        ]);

        try {
            DB::beginTransaction();

            $mahasiswa = Mahasiswa::with('tugasAkhir')->findOrFail($mahasiswaId);
            $tugasAkhirId = $mahasiswa->tugasAkhir->id;

            // Hapus pembimbing lama jika ada
            PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)
                ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                ->delete();

            // Simpan pembimbing baru
            foreach ($request->pembimbing as $index => $dosenId) {
                PeranDosenTa::create([
                    'dosen_id' => $dosenId,
                    'tugas_akhir_id' => $tugasAkhirId,
                    'peran' => 'pembimbing' . ($index + 1),
                ]);
            }

            DB::commit();

            return redirect()->route('penugasan-bimbingan.index')
                ->with('success', 'Pembimbing berhasil ditetapkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menetapkan pembimbing: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $tugasAkhirId)
    {
        $request->validate([
            'pembimbing1' => 'required|exists:dosen,id',
            'pembimbing2' => 'required|exists:dosen,id|different:pembimbing1',
        ]);

        try {
            DB::beginTransaction();

            PeranDosenTA::updateOrCreate(
                ['tugas_akhir_id' => $tugasAkhirId, 'peran' => 'pembimbing1'],
                ['dosen_id' => $request->pembimbing1]
            );

            PeranDosenTA::updateOrCreate(
                ['tugas_akhir_id' => $tugasAkhirId, 'peran' => 'pembimbing2'],
                ['dosen_id' => $request->pembimbing2]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Data pembimbing berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pembimbing: ' . $e->getMessage());
        }
    }
}
