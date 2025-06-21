<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalSidangAkhirController extends Controller
{
    public function dashboard()
    {
        $waitingSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->where('status', 'menunggu', ['tidak_lulus'])
            ->count();

        $waitingAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->whereIn('status', ['menunggu', 'tidak_lulus'])
            ->count();

        $scheduledSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->where('status', 'dijadwalkan')
            ->count();

        $scheduledAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->where('status', 'dijadwalkan')
            ->count();

        $pascaSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->whereIn('status', ['lulus', 'lulus_revisi'])
            ->count();

        $pascaAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->whereIn('status', ['lulus', 'lulus_revisi'])
            ->count();

        return view('admin.sidang.dashboard.dashboard', compact(
            'waitingSemproCount',
            'waitingAkhirCount',
            'scheduledSemproCount',
            'scheduledAkhirCount',
            'pascaSemproCount',
            'pascaAkhirCount'
        ));
    }

    public function sidangAkhir(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');

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
                        $query->where('jenis_sidang', 'akhir')->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        $mahasiswaLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'akhir')
                ->whereIn('status', ['lulus', 'lulus_revisi']);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'akhir')->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

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

        $perPage = 10;

        $mahasiswaMenunggu = $mahasiswaMenungguQuery->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query());
        $mahasiswaTidakLulus = $mahasiswaTidakLulusQuery->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query());
        $mahasiswaLulus = $mahasiswaLulusQuery->paginate($perPage, ['*'], 'lulus_page')->appends($request->query());
        $jadwalMahasiswa = $jadwalMahasiswaQuery->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

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
            $sidang = Sidang::with('tugasAkhir')->findOrFail($validated['sidang_id']);

            $bentrok = JadwalSidang::where('ruangan_id', $validated['ruangan_id'])
                ->where('tanggal', $validated['tanggal'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('waktu_mulai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                        ->orWhereBetween('waktu_selesai', [$validated['waktu_mulai'], $validated['waktu_selesai']])
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('waktu_mulai', '<', $validated['waktu_mulai'])
                                ->where('waktu_selesai', '>', $validated['waktu_selesai']);
                        });
                })
                ->exists();

            if ($bentrok) {
                return response()->json(['success' => false, 'message' => 'Ruangan sudah terpakai pada waktu tersebut.']);
            }

            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidang->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $jadwal = JadwalSidang::create([
                'sidang_id' => $sidang->id,
                'tanggal' => $validated['tanggal'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'ruangan_id' => $validated['ruangan_id'],
            ]);

            $sidang->update([
                'status' => 'dijadwalkan',
                'is_active' => true,
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal sidang akhir berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
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

        $sidang = $jadwal->sidang;
        $tugasAkhir = $sidang->tugasAkhir;

        foreach (['penguji1', 'penguji2', 'penguji3', 'penguji4'] as $peran) {
            $dosenId = $request->input($peran);
            if ($dosenId) {
                PeranDosenTA::updateOrCreate(
                    ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => $peran],
                    ['dosen_id' => $dosenId]
                );
            } else {
                PeranDosenTA::where('tugas_akhir_id', $tugasAkhir->id)->where('peran', $peran)->delete();
            }
        }

        $jadwal->load(['ruangan', 'sidang.tugasAkhir.peranDosenTa.dosen.user']);

        $penguji = $tugasAkhir->peranDosenTa->mapWithKeys(function ($pd) {
            return [$pd->peran => $pd->dosen->user->name ?? '-'];
        });

        return response()->json(['jadwal' => $jadwal, 'penguji' => $penguji]);
    }

    public function simpanPenguji(Request $request, $sidang_id)
    {
        $request->validate([
            'penguji' => 'required|array|min:1|max:4',
            'penguji.*' => 'exists:dosen,id',
        ]);

        $sidang = Sidang::findOrFail($sidang_id);
        $tugasAkhirId = $sidang->tugas_akhir_id;

        PeranDosenTA::where('tugas_akhir_id', $tugasAkhirId)
            ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->delete();

        foreach ($request->penguji as $index => $dosenId) {
            $peran = 'penguji' . ($index + 1);

            PeranDosenTA::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $tugasAkhirId,
                'peran' => $peran,
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

        $peranDosen = $tugasAkhir->peranDosenTa->mapWithKeys(function ($item) {
            return [$item->peran => $item->dosen];
        });

        $dosens = Dosen::with('user')->get();
        $ruangans = Ruangan::all();

        return view('admin.sidang.akhir.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans', 'peranDosen'));
    }

    public function tandaiSidang(Request $request, $sidang_id)
    {
        $request->validate([
            'status' => 'required|in:lulus,lulus_revisi,tidak_lulus'
        ]);

        $sidang = Sidang::where('id', $sidang_id)
            ->where('jenis_sidang', 'akhir')
            ->firstOrFail();

        if (in_array($sidang->status, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sidang sudah ditandai sebelumnya.'
            ]);
        }

        $sidang->status = $request->status;
        if ($request->status === 'tidak_lulus') {
            $sidang->is_active = false;
        }

        $sidang->save();

        return response()->json([
            'success' => true,
            'message' => 'Status sidang berhasil diperbarui.'
        ]);
    }
}
