<?php

namespace App\Http\Controllers;

use App\Models\{JadwalSidang, Ruangan, Sidang, Dosen, Mahasiswa, PeranDosenTA};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalSidangAkhirController extends Controller
{
    public function dashboard()
    {
        $counts = [
            'waitingSemproCount'     => $this->countSidang('proposal', ['menunggu', 'tidak_lulus']),
            'waitingAkhirCount'      => $this->countSidang('akhir', ['menunggu', 'tidak_lulus']),
            'scheduledSemproCount'   => $this->countSidang('proposal', ['dijadwalkan']),
            'scheduledAkhirCount'    => $this->countSidang('akhir', ['dijadwalkan']),
            'pascaSemproCount'       => $this->countSidang('proposal', ['lulus', 'lulus_revisi']),
            'pascaAkhirCount'        => $this->countSidang('akhir', ['lulus', 'lulus_revisi']),
        ];

        return view('admin.sidang.dashboard.dashboard', $counts);
    }

    private function countSidang($jenis, $statuses)
    {
        return Sidang::where('jenis_sidang', $jenis)
            ->whereIn('status', $statuses)
            ->count();
    }

    public function menungguSidangAkhir(Request $request)
    {
        [$menunggu, $tidakLulus] = $this->filterMahasiswaMenungguAkhir($request);

        return view('admin.sidang.akhir.views.mhs-sidang', [
            'mahasiswaMenunggu' => $menunggu,
            'mahasiswaTidakLulus' => $tidakLulus,
            'dosen' => Dosen::with('user')->get(),
            'ruanganList' => Ruangan::all(),
        ]);
    }

    private function filterMahasiswaMenungguAkhir(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');
        $perPage = 10;

        $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($q) {
            $q->where('jenis_sidang', 'akhir')->where('status', 'menunggu')->whereDoesntHave('jadwalSidang');
        })->with(['user', 'tugasAkhir.sidang', 'tugasAkhir.sidangTerakhir']);

        $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($q) {
            $q->where('jenis_sidang', 'akhir')->where('status', 'tidak_lulus')->where('is_active', false);
        })->whereDoesntHave('tugasAkhir.sidang', function ($q) {
            $q->where('jenis_sidang', 'akhir')->where('is_active', true);
        })->with(['user', 'tugasAkhir.sidang', 'tugasAkhir.sidangTerakhir']);

        foreach ([$mahasiswaMenungguQuery, $mahasiswaTidakLulusQuery] as $query) {
            if ($prodi) $query->where('prodi', 'like', "$prodi%");
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                      ->orWhere('nim', 'like', "%$search%");
                });
            }
        }

        return [
            $mahasiswaMenungguQuery->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query()),
            $mahasiswaTidakLulusQuery->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query()),
        ];
    }

    public function listJadwalAkhir(Request $request)
    {
        $allJadwal = $this->getFilteredJadwalAkhir($request);
        $jadwalList = $this->paginateCollection($allJadwal, 10);

        $detail = $request->filled('sidang_id') ? $this->getJadwalDetail($request->sidang_id) : [null, [], []];

        return view('admin.sidang.akhir.jadwal.jadwal-sidang-akhir', [
            'jadwalList' => $jadwalList,
            'jadwal'     => $detail[0],
            'dosens'     => $detail[1],
            'ruangans'   => $detail[2],
        ]);
    }

    private function getFilteredJadwalAkhir(Request $request)
    {
        return JadwalSidang::with(['sidang.tugasAkhir.mahasiswa.user', 'sidang.tugasAkhir.peranDosenTa.dosen.user', 'ruangan'])
            ->whereHas('sidang', function ($q) use ($request) {
                $q->where('jenis_sidang', 'akhir')
                  ->where('status', 'dijadwalkan')
                  ->where('is_active', true)
                  ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($request) {
                      if ($request->prodi) $q2->where('prodi', 'like', "$request->prodi%");
                      if ($request->search) {
                          $q2->where(fn($s) => $s->where('nim', 'like', "%$request->search%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$request->search%")));
                      }
                  });
            })
            ->get()->unique('sidang_id')->values();
    }

    private function paginateCollection($collection, $perPage)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($currentItems, $collection->count(), $perPage, $currentPage, [
            'path' => request()->url(), 'query' => request()->query()
        ]);
    }

    private function getJadwalDetail($sidangId)
    {
        $jadwal = JadwalSidang::with(['sidang.tugasAkhir.mahasiswa.user', 'ruangan', 'sidang.tugasAkhir.peranDosenTa.dosen.user'])
            ->where('sidang_id', $sidangId)->first();

        return [$jadwal, Dosen::with('user')->get(), Ruangan::all()];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sidang_id' => 'required|exists:sidang,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'ruangan_id' => 'required|exists:ruangan,id',
        ]);

        try {
            $sidang = Sidang::findOrFail($validated['sidang_id']);

            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidang->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $jadwal = JadwalSidang::create($validated);

            $sidang->update(['status' => 'dijadwalkan', 'is_active' => true]);

            return response()->json(['success' => true, 'message' => 'Jadwal sidang berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggalSidang' => 'required|date',
            'waktuMulai' => 'required',
            'waktuSelesai' => 'required',
            'ruangan' => 'required|exists:ruangan,id',
        ]);

        $jadwal = JadwalSidang::findOrFail($id);
        $jadwal->update([
            'tanggal' => $request->tanggalSidang,
            'waktu_mulai' => $request->waktuMulai,
            'waktu_selesai' => $request->waktuSelesai,
            'ruangan_id' => $request->ruangan,
        ]);

        $this->syncPenguji($jadwal->sidang->tugasAkhir->id, $request);

        $jadwal->load(['ruangan', 'sidang.tugasAkhir.peranDosenTa.dosen.user']);
        $penguji = $jadwal->sidang->tugasAkhir->peranDosenTa->mapWithKeys(fn($pd) => [$pd->peran => $pd->dosen->user->name ?? '-']);

        return response()->json(['jadwal' => $jadwal, 'penguji' => $penguji]);
    }

    private function syncPenguji($tugasAkhirId, Request $request)
    {
        foreach (['penguji1', 'penguji2', 'penguji3', 'penguji4'] as $peran) {
            $dosenId = $request->input($peran);
            if ($dosenId) {
                PeranDosenTa::updateOrCreate([
                    'tugas_akhir_id' => $tugasAkhirId,
                    'peran' => $peran,
                ], [
                    'dosen_id' => $dosenId
                ]);
            } else {
                PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)->where('peran', $peran)->delete();
            }
        }
    }

    public function simpanPenguji(Request $request, $sidang_id)
    {
        $request->validate([
            'penguji' => 'required|array|min:1|max:4',
            'penguji.*' => 'exists:dosen,id',
        ]);

        PeranDosenTA::where('tugas_akhir_id', $sidang_id)
            ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->delete();

        foreach ($request->penguji as $index => $dosenId) {
            PeranDosenTA::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $sidang_id,
                'peran' => 'penguji' . ($index + 1),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function show($sidang_id)
    {
        [$jadwal, $dosens, $ruangans] = $this->getJadwalDetail($sidang_id);

        return view('admin.sidang.akhir.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans'));
    }

    public function pascaSidangAkhir(Request $request)
    {
        $query = JadwalSidang::with(['sidang.tugasAkhir.mahasiswa.user', 'sidang.tugasAkhir.peranDosenTa.dosen.user', 'ruangan'])
            ->whereHas('sidang', function ($q) use ($request) {
                $q->where('jenis_sidang', 'akhir')
                  ->whereIn('status', ['lulus', 'lulus_revisi'])
                  ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($request) {
                      if ($request->prodi) $q2->where('prodi', 'like', "$request->prodi%");
                      if ($request->search) {
                          $q2->where(fn($q3) => $q3->where('nim', 'like', "%$request->search%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$request->search%")));
                      }
                  });
            });

        $allData = $query->get()->unique(fn($item) => optional($item->sidang->tugasAkhir)->mahasiswa_id)->values();
        $sidangSelesai = $this->paginateCollection($allData, 10);

        return view('admin.sidang.akhir.pasca.pasca-sidang', compact('sidangSelesai'));
    }

    public function tandaiSidang(Request $request, $sidang_id)
    {
        $request->validate(['status' => 'required|in:lulus,lulus_revisi,tidak_lulus']);

        $sidang = Sidang::where('id', $sidang_id)
            ->where('jenis_sidang', 'akhir')
            ->firstOrFail();

        if (in_array($sidang->status, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
            return response()->json(['success' => false, 'message' => 'Sidang sudah ditandai sebelumnya.']);
        }

        $sidang->update([
            'status' => $request->status,
            'is_active' => $request->status === 'tidak_lulus' ? false : true
        ]);

        return response()->json(['success' => true, 'message' => 'Status sidang berhasil diperbarui.']);
    }
}
