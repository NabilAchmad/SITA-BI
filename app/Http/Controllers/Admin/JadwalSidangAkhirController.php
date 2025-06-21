<?php

namespace App\Http\Controllers\Admin;

use App\Models\{JadwalSidang, Ruangan, Sidang, Dosen, Mahasiswa, PeranDosenTA};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;

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
        $prodi = $request->input('prodi');
        $search = $request->input('search');

        // ========================= 1. Mahasiswa Menunggu Penjadwalan Sidang Akhir ============================
        $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('status', 'menunggu')
                ->where('jenis_sidang', 'akhir')
                ->whereDoesntHave('jadwalSidang');
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('status', 'menunggu')
                            ->where('jenis_sidang', 'akhir')
                            ->whereDoesntHave('jadwalSidang');
                    }
                ]);
            }
        ]);

        // ========================= 2. Mahasiswa Tidak Lulus Sidang Akhir ============================
        $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'akhir')
                ->where('status', 'tidak_lulus')
                ->where('is_active', false);
        })->whereDoesntHave('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'akhir')
                ->where('is_active', true);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'akhir')
                            ->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // ========================= 3. Mahasiswa Lulus Sidang Akhir ============================
        $mahasiswaLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'akhir')
                ->whereIn('status', ['lulus', 'lulus_revisi']);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'akhir')
                            ->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // ========================= 4. Jadwal Mahasiswa Sidang Akhir ============================
        $jadwalMahasiswaQuery = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->whereHas('sidang', function ($q) use ($prodi, $search) {
            $q->where('jenis_sidang', 'akhir')
                ->where('status', 'dijadwalkan')
                ->where('is_active', true)
                ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
                    if ($prodi) {
                        $q2->where('prodi', 'like', $prodi . '%');
                    }
                    if ($search) {
                        $q2->where(function ($q3) use ($search) {
                            $q3->where('nim', 'like', '%' . $search . '%')
                                ->orWhereHas('user', function ($q4) use ($search) {
                                    $q4->where('name', 'like', '%' . $search . '%');
                                });
                        });
                    }
                });
        });

        // ========================= Apply Filter Prodi & Search ke semua query ============================
        if ($prodi) {
            $mahasiswaMenungguQuery->where('prodi', 'like', $prodi . '%');
            $mahasiswaTidakLulusQuery->where('prodi', 'like', $prodi . '%');
            $mahasiswaLulusQuery->where('prodi', 'like', $prodi . '%');
        }

        if ($search) {
            $mahasiswaMenungguQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });

            $mahasiswaTidakLulusQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });

            $mahasiswaLulusQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // ========================= Pagination ============================
        $perPage = 10;

        $mahasiswaMenunggu = $mahasiswaMenungguQuery->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query());
        $mahasiswaTidakLulus = $mahasiswaTidakLulusQuery->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query());
        $mahasiswaLulus = $mahasiswaLulusQuery->paginate($perPage, ['*'], 'lulus_page')->appends($request->query());
        $jadwalMahasiswa = $jadwalMahasiswaQuery->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

        // ========================= Data Tambahan ============================
        $dosen = Dosen::with('user')->get();
        $ruanganList = Ruangan::all();

        return view('admin.sidang.akhir.views.mhs-sidang', compact(
            'mahasiswaMenunggu',
            'mahasiswaTidakLulus',
            'mahasiswaLulus',
            'jadwalMahasiswa',
            'dosen',
            'ruanganList'
        ));
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
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'ruangan_id' => 'required|exists:ruangan,id',
        ]);

        try {
            $sidangId = $validated['sidang_id'];
            $tanggal = $validated['tanggal'];
            $mulai = $validated['waktu_mulai'];
            $selesai = $validated['waktu_selesai'];
            $ruanganId = $validated['ruangan_id'];

            // Ambil data sidang
            $sidang = Sidang::with('tugasAkhir')->findOrFail($sidangId);

            // Cek bentrok jadwal ruangan di tanggal & waktu yang sama
            $bentrok = JadwalSidang::where('ruangan_id', $ruanganId)
                ->where('tanggal', $tanggal)
                ->where(function ($query) use ($mulai, $selesai) {
                    $query->whereBetween('waktu_mulai', [$mulai, $selesai])
                        ->orWhereBetween('waktu_selesai', [$mulai, $selesai])
                        ->orWhere(function ($query) use ($mulai, $selesai) {
                            $query->where('waktu_mulai', '<', $mulai)
                                ->where('waktu_selesai', '>', $selesai);
                        });
                })
                ->exists();

            if ($bentrok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan sudah terpakai pada waktu tersebut.',
                ]);
            }

            // Nonaktifkan sidang lain milik tugas akhir ini
            $sidang = Sidang::findOrFail($validated['sidang_id']);

            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidang->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Simpan jadwal sidang
            $jadwal = new JadwalSidang();
            $jadwal->sidang_id = $sidangId;
            $jadwal->tanggal = $tanggal;
            $jadwal->waktu_mulai = $mulai;
            $jadwal->waktu_selesai = $selesai;
            $jadwal->ruangan_id = $ruanganId;
            $jadwal->save();

            // Update status sidang
            $sidang->status = 'dijadwalkan';
            $sidang->is_active = true;
            $sidang->save();
            $jadwal = JadwalSidang::create($validated);

            $sidang->update(['status' => 'dijadwalkan', 'is_active' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang akhir berhasil disimpan.',
            ]);
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

        $sidang = Sidang::findOrFail($sidang_id);
        $tugasAkhirId = $sidang->tugas_akhir_id;

        // Hapus penguji lama
        PeranDosenTA::where('tugas_akhir_id', $tugasAkhirId)
            ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->delete();

        foreach ($request->penguji as $index => $dosenId) {
            PeranDosenTA::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $tugasAkhirId,
                'peran' => 'penguji' . ($index + 1),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function show($sidang_id)
    {
        $jadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'ruangan',
            'sidang.tugasAkhir.peranDosenTa.dosen.user'
        ])->where('sidang_id', $sidang_id)->firstOrFail();

        $tugasAkhir = $jadwal->sidang->tugasAkhir;

        // Ambil dosen-dosen peran_dosen_ta yang sudah terdaftar untuk TA ini
        $peranDosen = $tugasAkhir->peranDosenTa->mapWithKeys(function ($item) {
            return [$item->peran => $item->dosen];
        });

        // Ambil semua dosen untuk dropdown select penguji
        $dosens = Dosen::with('user')->get();

        // Ambil semua ruangan untuk dropdown form edit
        $ruangans = Ruangan::all();
        [$jadwal, $dosens, $ruangans] = $this->getJadwalDetail($sidang_id);

        return view('admin.sidang.akhir.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans', 'peranDosen'));
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
