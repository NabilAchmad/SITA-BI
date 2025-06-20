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

        // ========================= 1. Mahasiswa Menunggu Penjadwalan ============================
        $mahasiswaMenungguQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('status', 'menunggu')
                ->where('jenis_sidang', 'proposal')
                ->whereDoesntHave('jadwalSidang');
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('status', 'menunggu')
                            ->where('jenis_sidang', 'proposal')
                            ->whereDoesntHave('jadwalSidang');
                    }
                ]);
            }
        ]);

        // ========================= 2. Mahasiswa Tidak Lulus Sempro ============================
        $mahasiswaTidakLulusQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')
                ->where('status', 'tidak_lulus')
                ->where('is_active', false);
        })->whereDoesntHave('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')
                ->where('is_active', true);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir',
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'proposal')
                            ->orderBy('created_at', 'desc');
                    }
                ]);
            }
        ]);

        // ========================= 3. Mahasiswa Lulus Sempro ============================
        $mahasiswaLulusSemproQuery = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal')
                ->whereIn('status', ['lulus', 'lulus_revisi']);
        })->with([
            'user',
            'tugasAkhir' => function ($query) {
                $query->with([
                    'sidangTerakhir.jadwalSidang', // tambahkan ini agar tanggal sidang ikut dimuat
                    'sidang' => function ($query) {
                        $query->where('jenis_sidang', 'proposal')
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
            $q->where('jenis_sidang', 'proposal')
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

        return view('admin.sidang.sempro.views.mhs-sidang', compact(
            'mahasiswaMenunggu',
            'mahasiswaTidakLulus',
            'mahasiswaLulusSempro',
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
}
