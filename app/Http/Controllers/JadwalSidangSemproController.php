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

class JadwalSidangSemproController extends Controller
{
    public function menungguSidangSempro(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');

        $user = $request->user();
        $roles = $user->roles->pluck('nama_role')->toArray();

        // Mahasiswa yang menunggu penjadwalan sidang sempro
        $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('status', 'menunggu')
                ->where('jenis_sidang', 'sempro')
                ->whereDoesntHave('jadwalSidang');
        })
            ->with([
                'user',
                'tugasAkhir' => function ($query) {
                    $query->with([
                        'sidangTerakhir',
                        'sidang' => function ($query) {
                            $query->where('status', 'menunggu')
                                ->where('jenis_sidang', 'sempro')
                                ->whereDoesntHave('jadwalSidang');
                        }
                    ]);
                }
            ]);

        // Mahasiswa yang tidak lulus sidang sempro dan belum aktif lagi
        $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')  // ganti ke 'sempro'
                ->where('status', 'tidak_lulus');
                //->where('is_active', false);
        })
            ->whereDoesntHave('tugasAkhir.sidang', function ($query) {
                $query->where('jenis_sidang', 'proposal');  // ganti ke 'sempro'
                    //->where('is_active', true);
            })
            ->with([
                'user',
                'tugasAkhir' => function ($query) {
                    $query->with([
                        'sidangTerakhir',
                        'sidang' => function ($q) {
                            $q->where('jenis_sidang', 'proposal')  // ganti ke 'sempro'
                                ->orderBy('created_at', 'desc');
                        }
                    ]);
                }
            ]);

        // Filter berdasarkan Prodi
        if ($prodi) {
            $mahasiswaMenungguQuery->where('prodi', 'like', $prodi . '%');
            $mahasiswaTidakLulusQuery->where('prodi', 'like', $prodi . '%');
        }

        // Filter berdasarkan pencarian nama mahasiswa atau NIM
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
        }

        $perPage = 10;

        $mahasiswaMenunggu = $mahasiswaMenungguQuery
            ->paginate($perPage, ['*'], 'menunggu_page')
            ->appends($request->only('prodi', 'search'));

        $mahasiswaTidakLulus = $mahasiswaTidakLulusQuery
            ->paginate($perPage, ['*'], 'tidaklulus_page')
            ->appends($request->only('prodi', 'search'));

        $dosen = Dosen::with('user')->get();
        $ruanganList = Ruangan::all();

        if (in_array('kaprodi', $roles)) {
            return view('kaprodi.sidang.sempro.views.mhs-sidang', compact(
                'mahasiswaMenunggu',
                'mahasiswaTidakLulus',
                'dosen',
                'ruanganList'
            ));
        } elseif (in_array('kajur', $roles)) {
            return view('kajur.sidang.sempro.views.mhs-sidang', compact(
                'mahasiswaMenunggu',
                'mahasiswaTidakLulus',
                'dosen',
                'ruanganList'
            ));
        } else {
            return view('admin.sidang.sempro.views.mhs-sidang', compact(
                'mahasiswaMenunggu',
                'mahasiswaTidakLulus',
                'dosen',
                'ruanganList'
            ));
        }
    }

    // Menampilkan daftar jadwal sidang SEMINAR PROPOSAL
    public function listJadwalSempro(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search'); // Input pencarian dari search bar

        // Ambil semua jadwal sidang SEMPRO dengan status 'dijadwalkan' dan is_active = true
        $allJadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])
            ->whereHas('sidang', function ($q) use ($prodi, $search) {
                $q->where('jenis_sidang', 'proposal') // <- Ganti jadi proposal
                    ->where('status', 'dijadwalkan')
                    ->where('is_active', true)
                    ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
                        if ($prodi) {
                            $q2->where('prodi', 'like', $prodi . '%');
                        }

                        if ($search) {
                            $q2->where(function ($s) use ($search) {
                                $s->where('nim', 'like', '%' . $search . '%')
                                    ->orWhereHas('user', function ($u) use ($search) {
                                        $u->where('name', 'like', '%' . $search . '%');
                                    });
                            });
                        }
                    });
            })
            ->get()
            ->unique('sidang_id')
            ->values();

        // Pagination manual untuk Collection
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allJadwal->slice(($currentPage - 1) * $perPage, $perPage);
        $jadwalList = new LengthAwarePaginator(
            $currentItems,
            $allJadwal->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Detail jadwal sidang jika sidang_id diberikan (untuk modal/form edit mungkin)
        $jadwal = null;
        $dosens = null;
        $ruangans = null;

        if ($request->has('sidang_id')) {
            $jadwal = JadwalSidang::with([
                'sidang.tugasAkhir.mahasiswa.user',
                'ruangan',
                'sidang.tugasAkhir.peranDosenTa.dosen.user'
            ])
                ->where('sidang_id', $request->sidang_id)
                ->whereHas('sidang', function ($q) {
                    $q->where('jenis_sidang', 'proposal') // <- Ganti jadi proposal
                        ->where('status', 'dijadwalkan')
                        ->where('is_active', true);
                })
                ->first();

            $dosens = Dosen::with('user')->get();
            $ruangans = Ruangan::all();
        }

        return view('admin.sidang.sempro.jadwal.jadwal-sidang-sempro', [
            'jadwalList' => $jadwalList,
            'jadwal' => $jadwal,
            'dosens' => $dosens ?? [],
            'ruangans' => $ruangans ?? [],
        ]);
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
            $sidangId = $validated['sidang_id'];
            $tanggal = $validated['tanggal'];
            $mulai = $validated['waktu_mulai'];
            $selesai = $validated['waktu_selesai'];
            $ruanganId = $validated['ruangan_id'];

            // Periksa apakah ruangan sudah dipakai di waktu yang sama
            $jadwalBentrok = JadwalSidang::where('ruangan_id', $ruanganId)
                ->where('tanggal', $tanggal)
                ->where(function ($query) use ($mulai, $selesai) {
                    $query->whereBetween('waktu_mulai', [$mulai, $selesai])
                        ->orWhereBetween('waktu_selesai', [$mulai, $selesai])
                        ->orWhere(function ($query) use ($mulai, $selesai) {
                            $query->where('waktu_mulai', '<', $mulai)
                                ->where('waktu_selesai', '>', $selesai);
                        });
                })->exists();

            if ($jadwalBentrok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan sudah terpakai pada waktu tersebut.'
                ]);
            }

            // Nonaktifkan sidang lama yang aktif (jika ada)
            $sidang = Sidang::findOrFail($sidangId);
            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidangId)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Simpan jadwal baru
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

            return redirect()->back()->with('success', 'Jadwal sidang berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan jadwal.');
        }
    }

    public function show($sidang_id)
    {
        $jadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'ruangan',
            'sidang.tugasAkhir.peranDosenTa.dosen.user'
        ])->where('sidang_id', $sidang_id)->firstOrFail();

        // Ambil semua dosen dengan user-nya untuk dropdown select penguji
        $dosens = Dosen::with('user')->get();

        // Ambil semua ruangan untuk dropdown form edit
        $ruangans = Ruangan::all();

        return view('admin.sidang.sempro.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans'));
    }

    public function pascaSidangSempro(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');

        $query = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])
            ->whereHas('sidang', function ($q) use ($prodi, $search) {
                $q->where('jenis_sidang', 'proposal') // Ubah dari 'akhir' ke 'sempro'
                    ->whereIn('status', ['lulus', 'lulus_revisi']) // Tetap sama
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

        $allData = $query->get()->unique(fn($item) => optional($item->sidang->tugasAkhir)->mahasiswa_id)->values();

        // Manual pagination
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $sidangSelesai = new LengthAwarePaginator(
            $currentItems,
            $allData->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.sidang.sempro.pasca.pasca-sidang', compact('sidangSelesai'));
    }

    public function tandaiSidangSempro(Request $request, $sidang_id)
    {
        $request->validate([
            'status' => 'required|in:lulus,lulus_revisi,tidak_lulus'
        ]);

        $sidang = Sidang::where('id', $sidang_id)
            ->where('jenis_sidang', 'proposal')
            ->firstOrFail();

        if (in_array($sidang->status, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sidang sudah ditandai sebelumnya.'
            ]);
        }

        // Update status sidang
        $sidang->status = $request->status;

        // Jika tidak lulus, tandai sidang ini sebagai tidak aktif
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
