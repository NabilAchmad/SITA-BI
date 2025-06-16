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
        // Menunggu Sidang Sempro (proposal yang statusnya menunggu)
        $waitingSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->where('status', 'menunggu', ['tidak_lulus'])
            ->count();

        // Menunggu Sidang Akhir (akhir yang statusnya menunggu)
        $waitingAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->whereIn('status', ['menunggu', 'tidak_lulus'])
            ->count();

        // Jadwal Sidang Sempro (proposal yang sudah dijadwalkan)
        $scheduledSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->where('status', 'dijadwalkan')
            ->count();

        // Jadwal Sidang Akhir (akhir yang sudah dijadwalkan)
        $scheduledAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->where('status', 'dijadwalkan')
            ->count();

        // Pasca Sidang Sempro (proposal yang statusnya lulus atau lulus_revisi)
        $pascaSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->whereIn('status', ['lulus', 'lulus_revisi'])
            ->count();

        // Pasca Sidang Akhir (akhir yang statusnya lulus atau lulus_revisi)
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

    public function SidangAkhir(Request $request)
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

            // 1. Nonaktifkan sidang lama yang aktif untuk tugas akhir yang sama
            $sidang = Sidang::findOrFail($sidangId);

            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidangId)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // 2. Simpan jadwal sidang baru
            $jadwal = new JadwalSidang();
            $jadwal->sidang_id = $sidangId;
            $jadwal->tanggal = $validated['tanggal'];
            $jadwal->waktu_mulai = $validated['waktu_mulai'];
            $jadwal->waktu_selesai = $validated['waktu_selesai'];
            $jadwal->ruangan_id = $validated['ruangan_id'];
            $jadwal->save();

            // 3. Update status sidang dan aktifkan sidang baru
            $sidang->status = 'dijadwalkan';
            $sidang->is_active = true;
            $sidang->save();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
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
        $jadwal->tanggal = $request->tanggalSidang;
        $jadwal->waktu_mulai = $request->waktuMulai;
        $jadwal->waktu_selesai = $request->waktuSelesai;
        $jadwal->ruangan_id = $request->ruangan;
        $jadwal->save();

        // Perbarui dosen penguji
        $sidang = $jadwal->sidang;
        $tugasAkhir = $sidang->tugasAkhir;

        $peranList = ['penguji1', 'penguji2', 'penguji3', 'penguji4'];
        foreach ($peranList as $peran) {
            $dosenId = $request->input($peran);
            if ($dosenId) {
                PeranDosenTa::updateOrCreate(
                    ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => $peran],
                    ['dosen_id' => $dosenId]
                );
            } else {
                // Hapus jika kosong
                PeranDosenTa::where('tugas_akhir_id', $tugasAkhir->id)->where('peran', $peran)->delete();
            }
        }

        // Muat ulang semua relasi
        $jadwal->load([
            'ruangan',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
        ]);

        // Ambil nama-nama dosen penguji
        $penguji = $tugasAkhir->peranDosenTa->mapWithKeys(function ($pd) {
            return [$pd->peran => $pd->dosen->user->name ?? '-'];
        });

        return response()->json([
            'jadwal' => $jadwal,
            'penguji' => $penguji,
        ]);
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

        // Ambil dosen-dosen peran_dosen_ta yang sudah terdaftar untuk TA ini
        $peranDosen = $tugasAkhir->peranDosenTa->mapWithKeys(function ($item) {
            return [$item->peran => $item->dosen];
        });

        // Ambil semua dosen untuk dropdown select penguji
        $dosens = Dosen::with('user')->get();

        // Ambil semua ruangan untuk dropdown form edit
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
