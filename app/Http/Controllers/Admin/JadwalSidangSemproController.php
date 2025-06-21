<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;

class JadwalSidangSemproController extends Controller
{
    public function SidangSempro(Request $request)
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');

        $user = $request->user();
        $roles = $user->roles->pluck('nama_role')->toArray();

        // ========================= 1. Mahasiswa Menunggu Penjadwalan ============================
        $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('status', 'menunggu')
                ->where('jenis_sidang', 'sempro')
                ->whereDoesntHave('jadwalSidang');
        })->with([
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

        // ========================= 2. Mahasiswa Tidak Lulus Sempro ============================
        $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'sempro')
                ->where('status', 'tidak_lulus')
                ->where('is_active', false);
        })->whereDoesntHave('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'sempro')
                ->where('is_active', true);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'sempro')
                            ->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // ========================= 3. Mahasiswa Lulus Sempro ============================
        $mahasiswaLulusSemproQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'sempro')
                ->whereIn('status', ['lulus', 'lulus_revisi']);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir.jadwalSidang', // tambahkan ini agar tanggal sidang ikut dimuat
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'sempro')
                            ->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // ========================= 4. Jadwal Mahasiswa Sidang Sempro (Format Query Builder) ============================
        $jadwalMahasiswaQuery = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->whereHas('sidang', function ($q) use ($prodi, $search) {
            $q->where('jenis_sidang', 'sempro')
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

        // ========================= Apply filter untuk semua query ============================
        if ($prodi) {
            $mahasiswaMenungguQuery->where('prodi', 'like', $prodi . '%');
            $mahasiswaTidakLulusQuery->where('prodi', 'like', $prodi . '%');
            $mahasiswaLulusSemproQuery->where('prodi', 'like', $prodi . '%');
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

            $mahasiswaLulusSemproQuery->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // ========================= Paginate semua query ============================
        $perPage = 10;

        $mahasiswaMenunggu = $mahasiswaMenungguQuery->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query());
        $mahasiswaTidakLulus = $mahasiswaTidakLulusQuery->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query());
        $mahasiswaLulusSempro = $mahasiswaLulusSemproQuery->paginate($perPage, ['*'], 'lulus_page')->appends($request->query());
        $jadwalMahasiswa = $jadwalMahasiswaQuery->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

        // ========================= Data tambahan ============================
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

            // Cek bentrok jadwal
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

            // Nonaktifkan sidang lama
            $sidang = Sidang::findOrFail($sidangId);
            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidangId)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Simpan jadwal baru
            JadwalSidang::create([
                'sidang_id' => $sidangId,
                'tanggal' => $tanggal,
                'waktu_mulai' => $mulai,
                'waktu_selesai' => $selesai,
                'ruangan_id' => $ruanganId,
            ]);

            $sidang->update([
                'status' => 'dijadwalkan',
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan jadwal.',
                'error' => $e->getMessage(), // opsional untuk debug
            ], 500);
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

//     <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\JadwalSidang;
// use App\Models\Ruangan;
// use App\Models\Sidang;
// use App\Models\Dosen;
// use App\Models\Mahasiswa;
// use App\Models\PeranDosenTA;
// use Illuminate\Pagination\LengthAwarePaginator;

// class JadwalSidangAkhirController extends Controller
// {

//     public function dashboard()
//     {
//         // Menunggu Sidang Sempro (proposal yang statusnya menunggu)
//         $waitingSemproCount = Sidang::where('jenis_sidang', 'proposal')
//             ->where('status', 'menunggu', ['tidak_lulus'])
//             ->count();

//         // Menunggu Sidang Akhir (akhir yang statusnya menunggu)
//         $waitingAkhirCount = Sidang::where('jenis_sidang', 'akhir')
//             ->whereIn('status', ['menunggu', 'tidak_lulus'])
//             ->count();

//         // Jadwal Sidang Sempro (proposal yang sudah dijadwalkan)
//         $scheduledSemproCount = Sidang::where('jenis_sidang', 'proposal')
//             ->where('status', 'dijadwalkan')
//             ->count();

//         // Jadwal Sidang Akhir (akhir yang sudah dijadwalkan)
//         $scheduledAkhirCount = Sidang::where('jenis_sidang', 'akhir')
//             ->where('status', 'dijadwalkan')
//             ->count();

//         // Pasca Sidang Sempro (proposal yang statusnya lulus atau lulus_revisi)
//         $pascaSemproCount = Sidang::where('jenis_sidang', 'proposal')
//             ->whereIn('status', ['lulus', 'lulus_revisi'])
//             ->count();

//         // Pasca Sidang Akhir (akhir yang statusnya lulus atau lulus_revisi)
//         $pascaAkhirCount = Sidang::where('jenis_sidang', 'akhir')
//             ->whereIn('status', ['lulus', 'lulus_revisi'])
//             ->count();

//         return view('admin.sidang.dashboard.dashboard', compact(
//             'waitingSemproCount',
//             'waitingAkhirCount',
//             'scheduledSemproCount',
//             'scheduledAkhirCount',
//             'pascaSemproCount',
//             'pascaAkhirCount'
//         ));
//     }

//     public function menungguSidangAkhir(Request $request)
//     {
//         $prodi = $request->input('prodi');
//         $search = $request->input('search');

//         // Mahasiswa menunggu penjadwalan sidang akhir
//         $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
//             $query->where('status', 'menunggu')
//                 ->where('jenis_sidang', 'akhir')
//                 ->whereDoesntHave('jadwalSidang');
//         })
//             ->with([
//                 'user',
//                 'tugasAkhir' => function ($query) {
//                     $query->with([
//                         'sidangTerakhir',
//                         'sidang' => function ($query) {
//                             $query->where('status', 'menunggu')
//                                 ->where('jenis_sidang', 'akhir')
//                                 ->whereDoesntHave('jadwalSidang');
//                         }
//                     ]);
//                 }
//             ]);

//         // Mahasiswa yang tidak lulus dan belum aktif lagi
//         $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
//             $query->where('jenis_sidang', 'akhir')
//                 ->where('status', 'tidak_lulus')
//                 ->where('is_active', false);
//         })
//             ->whereDoesntHave('tugasAkhir.sidang', function ($query) {
//                 $query->where('jenis_sidang', 'akhir')
//                     ->where('is_active', true);
//             })
//             ->with([
//                 'user',
//                 'tugasAkhir' => function ($query) {
//                     $query->with([
//                         'sidangTerakhir',
//                         'sidang' => function ($q) {
//                             $q->where('jenis_sidang', 'akhir')
//                                 ->orderBy('created_at', 'desc');
//                         }
//                     ]);
//                 }
//             ]);

//         // Filter berdasarkan Prodi
//         if ($prodi) {
//             $mahasiswaMenungguQuery->where('prodi', 'like', $prodi . '%');
//             $mahasiswaTidakLulusQuery->where('prodi', 'like', $prodi . '%');
//         }

//         // Filter berdasarkan pencarian nama mahasiswa atau NIM
//         if ($search) {
//             $mahasiswaMenungguQuery->where(function ($q) use ($search) {
//                 $q->whereHas('user', function ($u) use ($search) {
//                     $u->where('name', 'like', '%' . $search . '%');
//                 })->orWhere('nim', 'like', '%' . $search . '%');
//             });

//             $mahasiswaTidakLulusQuery->where(function ($q) use ($search) {
//                 $q->whereHas('user', function ($u) use ($search) {
//                     $u->where('name', 'like', '%' . $search . '%');
//                 })->orWhere('nim', 'like', '%' . $search . '%');
//             });
//         }

//         $perPage = 10;

//         $mahasiswaMenunggu = $mahasiswaMenungguQuery
//             ->paginate($perPage, ['*'], 'menunggu_page')
//             ->appends($request->only('prodi', 'search'));

//         $mahasiswaTidakLulus = $mahasiswaTidakLulusQuery
//             ->paginate($perPage, ['*'], 'tidaklulus_page')
//             ->appends($request->only('prodi', 'search'));

//         $dosen = Dosen::with('user')->get();
//         $ruanganList = Ruangan::all();

//         return view('admin.sidang.akhir.views.mhs-sidang', compact(
//             'mahasiswaMenunggu',
//             'mahasiswaTidakLulus',
//             'dosen',
//             'ruanganList'
//         ));
//     }

//     public function listJadwalAkhir(Request $request)
//     {
//         $prodi = $request->input('prodi');
//         $search = $request->input('search'); // Ambil input pencarian dari search bar

//         // Ambil semua jadwal sidang akhir dengan status 'dijadwalkan' dan is_active = true
//         $allJadwal = JadwalSidang::with([
//             'sidang.tugasAkhir.mahasiswa.user',
//             'sidang.tugasAkhir.peranDosenTa.dosen.user',
//             'ruangan'
//         ])
//             ->whereHas('sidang', function ($q) use ($prodi, $search) {
//                 $q->where('jenis_sidang', 'akhir')
//                     ->where('status', 'dijadwalkan')
//                     ->where('is_active', true)
//                     ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
//                         if ($prodi) {
//                             $q2->where('prodi', 'like', $prodi . '%');
//                         }

//                         if ($search) {
//                             $q2->where(function ($s) use ($search) {
//                                 $s->where('nim', 'like', '%' . $search . '%')
//                                     ->orWhereHas('user', function ($u) use ($search) {
//                                         $u->where('name', 'like', '%' . $search . '%');
//                                     });
//                             });
//                         }
//                     });
//             })
//             ->get()
//             ->unique('sidang_id')
//             ->values(); // hasilnya Collection

//         // Pagination manual untuk Collection
//         $perPage = 10;
//         $currentPage = LengthAwarePaginator::resolveCurrentPage();
//         $currentItems = $allJadwal->slice(($currentPage - 1) * $perPage, $perPage);
//         $jadwalList = new LengthAwarePaginator(
//             $currentItems,
//             $allJadwal->count(),
//             $perPage,
//             $currentPage,
//             ['path' => request()->url(), 'query' => request()->query()]
//         );

//         // Ambil detail jika ada sidang_id
//         $jadwal = null;
//         $dosens = null;
//         $ruangans = null;

//         if ($request->has('sidang_id')) {
//             $jadwal = JadwalSidang::with([
//                 'sidang.tugasAkhir.mahasiswa.user',
//                 'ruangan',
//                 'sidang.tugasAkhir.peranDosenTa.dosen.user'
//             ])
//                 ->where('sidang_id', $request->sidang_id)
//                 ->whereHas('sidang', function ($q) {
//                     $q->where('jenis_sidang', 'akhir')
//                         ->where('status', 'dijadwalkan')
//                         ->where('is_active', true);
//                 })
//                 ->first();

//             $dosens = Dosen::with('user')->get();
//             $ruangans = Ruangan::all();
//         }

//         return view('admin.sidang.akhir.jadwal.jadwal-sidang-akhir', [
//             'jadwalList' => $jadwalList,
//             'jadwal' => $jadwal,
//             'dosens' => $dosens ?? [],
//             'ruangans' => $ruangans ?? [],
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'sidang_id' => 'required|exists:sidang,id',
//             'tanggal' => 'required|date',
//             'waktu_mulai' => 'required',
//             'waktu_selesai' => 'required|after:waktu_mulai',
//             'ruangan_id' => 'required|exists:ruangan,id',
//         ]);

//         try {
//             $sidangId = $validated['sidang_id'];

//             // 1. Nonaktifkan sidang lama yang aktif untuk tugas akhir yang sama
//             $sidang = Sidang::findOrFail($sidangId);

//             Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
//                 ->where('id', '!=', $sidangId)
//                 ->where('is_active', true)
//                 ->update(['is_active' => false]);

//             // 2. Simpan jadwal sidang baru
//             $jadwal = new JadwalSidang();
//             $jadwal->sidang_id = $sidangId;
//             $jadwal->tanggal = $validated['tanggal'];
//             $jadwal->waktu_mulai = $validated['waktu_mulai'];
//             $jadwal->waktu_selesai = $validated['waktu_selesai'];
//             $jadwal->ruangan_id = $validated['ruangan_id'];
//             $jadwal->save();

//             // 3. Update status sidang dan aktifkan sidang baru
//             $sidang->status = 'dijadwalkan';
//             $sidang->is_active = true;
//             $sidang->save();

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Jadwal sidang berhasil disimpan.',
//             ]);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Terjadi kesalahan saat menyimpan data.',
//             ], 500);
//         }
//     }

//     public function update(Request $request, $id)
//     {
//         $request->validate([
//             'tanggalSidang' => 'required|date',
//             'waktuMulai' => 'required',
//             'waktuSelesai' => 'required',
//             'ruangan' => 'required|exists:ruangan,id',
//         ]);

//         $jadwal = JadwalSidang::findOrFail($id);
//         $jadwal->tanggal = $request->tanggalSidang;
//         $jadwal->waktu_mulai = $request->waktuMulai;
//         $jadwal->waktu_selesai = $request->waktuSelesai;
//         $jadwal->ruangan_id = $request->ruangan;
//         $jadwal->save();

//         // Perbarui dosen penguji
//         $sidang = $jadwal->sidang;
//         $tugasAkhir = $sidang->tugasAkhir;

//         $peranList = ['penguji1', 'penguji2', 'penguji3', 'penguji4'];
//         foreach ($peranList as $peran) {
//             $dosenId = $request->input($peran);
//             if ($dosenId) {
//                 PeranDosenTa::updateOrCreate(
//                     ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => $peran],
//                     ['dosen_id' => $dosenId]
//                 );
//             } else {
//                 // Hapus jika kosong
//                 PeranDosenTa::where('tugas_akhir_id', $tugasAkhir->id)->where('peran', $peran)->delete();
//             }
//         }

//         // Muat ulang semua relasi
//         $jadwal->load([
//             'ruangan',
//             'sidang.tugasAkhir.peranDosenTa.dosen.user',
//         ]);

//         // Ambil nama-nama dosen penguji
//         $penguji = $tugasAkhir->peranDosenTa->mapWithKeys(function ($pd) {
//             return [$pd->peran => $pd->dosen->user->name ?? '-'];
//         });

//         return response()->json([
//             'jadwal' => $jadwal,
//             'penguji' => $penguji,
//         ]);
//     }

//     public function simpanPenguji(Request $request, $sidang_id)
//     {
//         $request->validate([
//             'penguji' => 'required|array|min:1|max:4',
//             'penguji.*' => 'exists:dosen,id',
//         ]);

//         // Hapus dulu data penguji yang lama untuk sidang ini supaya tidak duplikat
//         PeranDosenTA::where('tugas_akhir_id', $sidang_id)
//             ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
//             ->delete();

//         // Simpan penguji baru sesuai urutan yang dipilih
//         foreach ($request->penguji as $index => $dosenId) {
//             $peran = 'penguji' . ($index + 1);

//             PeranDosenTA::create([
//                 'dosen_id' => $dosenId,
//                 'tugas_akhir_id' => $sidang_id,
//                 'peran' => $peran,
//             ]);
//         }

//         // Ini penting untuk AJAX:
//         return response()->json(['success' => true]);
//     }

//     public function show($sidang_id)
//     {
//         $jadwal = JadwalSidang::with([
//             'sidang.tugasAkhir.mahasiswa.user',
//             'ruangan',
//             'sidang.tugasAkhir.peranDosenTa.dosen.user'
//         ])->where('sidang_id', $sidang_id)->firstOrFail();

//         // Ambil semua dosen dengan user-nya untuk dropdown select penguji
//         $dosens = Dosen::with('user')->get();

//         // Ambil semua ruangan untuk dropdown form edit
//         $ruangans = Ruangan::all();

//         return view('admin.sidang.akhir.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans'));
//     }

//     public function pascaSidangAkhir(Request $request)
//     {
//         $prodi = $request->input('prodi');
//         $search = $request->input('search');

//         $query = JadwalSidang::with([
//             'sidang.tugasAkhir.mahasiswa.user',
//             'sidang.tugasAkhir.peranDosenTa.dosen.user',
//             'ruangan'
//         ])
//             ->whereHas('sidang', function ($q) use ($prodi, $search) {
//                 $q->where('jenis_sidang', 'akhir')
//                     ->whereIn('status', ['lulus', 'lulus_revisi'])
//                     ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
//                         if ($prodi) {
//                             $q2->where('prodi', 'like', $prodi . '%');
//                         }

//                         if ($search) {
//                             $q2->where(function ($q3) use ($search) {
//                                 $q3->where('nim', 'like', '%' . $search . '%')
//                                     ->orWhereHas('user', function ($q4) use ($search) {
//                                         $q4->where('name', 'like', '%' . $search . '%');
//                                     });
//                             });
//                         }
//                     });
//             });

//         $allData = $query->get()->unique(fn($item) => optional($item->sidang->tugasAkhir)->mahasiswa_id)->values();

//         // Manual pagination
//         $perPage = 10;
//         $currentPage = LengthAwarePaginator::resolveCurrentPage();
//         $currentItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->values();

//         $sidangSelesai = new LengthAwarePaginator(
//             $currentItems,
//             $allData->count(),
//             $perPage,
//             $currentPage,
//             ['path' => request()->url(), 'query' => request()->query()]
//         );

//         return view('admin.sidang.akhir.pasca.pasca-sidang', compact('sidangSelesai'));
//     }

//     public function tandaiSidang(Request $request, $sidang_id)
//     {
//         $request->validate([
//             'status' => 'required|in:lulus,lulus_revisi,tidak_lulus'
//         ]);

//         $sidang = Sidang::where('id', $sidang_id)
//             ->where('jenis_sidang', 'akhir')
//             ->firstOrFail();

//         if (in_array($sidang->status, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Sidang sudah ditandai sebelumnya.'
//             ]);
//         }

//         // Update status sidang
//         $sidang->status = $request->status;

//         // Jika tidak lulus, tandai sidang ini sebagai tidak aktif
//         if ($request->status === 'tidak_lulus') {
//             $sidang->is_active = false;
//         }

//         $sidang->save();

//         return response()->json([
//             'success' => true,
//             'message' => 'Status sidang berhasil diperbarui.'
//         ]);
//     }
// }

}
