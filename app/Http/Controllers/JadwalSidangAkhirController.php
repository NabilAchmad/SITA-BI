<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA;
use Illuminate\Support\Facades\DB;
use App\Models\BeritaAcaraPraSidang;
use App\Models\BeritaAcaraPascaSidang;

class JadwalSidangAkhirController extends Controller
{

    public function dashboard()
    {
        // Mahasiswa yang menunggu penjadwalan sidang proposal
        $waitingSemproCount = Sidang::where('jenis_sidang', 'proposal')
            ->where('status', 'dijadwalkan')->count();

        // Mahasiswa yang menunggu penjadwalan sidang akhir
        $waitingAkhirCount = Sidang::where('jenis_sidang', 'akhir')
            ->where('status', 'dijadwalkan')->count();

        // Jumlah jadwal sidang proposal
        $jadwalSemproCount = JadwalSidang::whereHas('sidang', function ($query) {
            $query->where('jenis_sidang', 'proposal');
        })->count();

        // Jumlah jadwal sidang akhir
        $jadwalAkhirCount = JadwalSidang::whereHas('sidang', function ($query) {
            $query->where('jenis_sidang', 'akhir');
        })->count();

        // Jumlah berita acara pra sidang (sempro)
        $pascaSemproCount = BeritaAcaraPraSidang::count();

        // Jumlah berita acara pasca sidang (akhir)
        $pascaAkhirCount = BeritaAcaraPascaSidang::count();

        return view('admin.sidang.dashboard.dashboard', compact(
            'waitingSemproCount',
            'waitingAkhirCount',
            'jadwalSemproCount',
            'jadwalAkhirCount',
            'pascaSemproCount',
            'pascaAkhirCount'
        ));
    }

    public function MenungguSidangAkhir()
    {
        $mahasiswa = Mahasiswa::whereHas('tugasAkhir.sidang', function ($query) {
            $query->where('status', 'dijadwalkan')
                ->where('jenis_sidang', 'akhir')
                ->whereDoesntHave('jadwalSidang');
        })
            ->with([
                'user',
                'tugasAkhir.sidang' => function ($query) {
                    $query->where('status', 'dijadwalkan')
                        ->where('jenis_sidang', 'akhir')
                        ->whereDoesntHave('jadwalSidang');
                },
            ])
            ->get();

        $dosen = Dosen::with('user')->get();

        $ruanganList = Ruangan::all();

        return view('admin.sidang.akhir.views.mhs-sidang', compact('mahasiswa', 'dosen', 'ruanganList'));
    }

    public function listJadwal()
    {
        $jadwalList = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])
            ->whereHas('sidang', function ($q) {
                $q->where('status', 'dijadwalkan');
            })
            ->get();

        return view('admin.sidang.akhir.jadwal.jadwal-sidang-akhir', compact('jadwalList'));
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

        // Simpan data jadwal sidang ke DB (sesuaikan dengan model dan logika kamu)
        try {
            // Contoh:
            $jadwal = new JadwalSidang();
            $jadwal->sidang_id = $validated['sidang_id'];
            $jadwal->tanggal = $validated['tanggal'];
            $jadwal->waktu_mulai = $validated['waktu_mulai'];
            $jadwal->waktu_selesai = $validated['waktu_selesai'];
            $jadwal->ruangan_id = $validated['ruangan_id'];
            $jadwal->save();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            // Bisa log error jika perlu
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

    public function showFormPenguji($sidang_id)
    {
        $sidang = Sidang::with('tugasAkhir.mahasiswa.user')->findOrFail($sidang_id);
        $dosen = Dosen::with('user')->get(); // ambil semua dosen
        $jadwalList = JadwalSidang::where('sidang_id', $sidang_id)->get();


        return view('admin.sidang.jadwal.views.pilihPenguji', compact('sidang', 'dosen', 'jadwalList'));
    }

    public function simpanPenguji(Request $request, $sidang_id)
    {
        $request->validate([
            'penguji' => 'required|array|min:1|max:4',
            'penguji.*' => 'exists:dosen,id',
        ]);

        // Hapus dulu data penguji yang lama untuk sidang ini supaya tidak duplikat
        PeranDosenTA::where('tugas_akhir_id', $sidang_id)
            ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->delete();

        // Simpan penguji baru sesuai urutan yang dipilih
        foreach ($request->penguji as $index => $dosenId) {
            $peran = 'penguji' . ($index + 1);

            PeranDosenTA::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $sidang_id,
                'peran' => $peran,
            ]);
        }

        // Ini penting untuk AJAX:
        return response()->json(['success' => true]);
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

        return view('admin.sidang.akhir.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans'));
    }

    public function pascaSidangAkhir()
    {
        $sidangSelesai = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user', // pastikan relasi ini valid
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])
            ->whereHas('sidang', fn($q) => $q->where('status', 'selesai'))
            ->get()
            ->unique(fn($item) => optional($item->sidang->tugasAkhir)->mahasiswa_id);

        return view('admin.sidang.akhir.pasca.pasca-sidang', compact('sidangSelesai'));
    }

    public function tandaiSidang($sidang_id)
    {
        try {
            $sidang = Sidang::with('tugasAkhir.mahasiswa')->findOrFail($sidang_id);

            if ($sidang->status === 'selesai') {
                return redirect()->back()->with('info', 'Sidang sudah ditandai selesai.');
            }

            $sidang->status = 'selesai';
            $sidang->save();

            return redirect()->route('jadwal-sidang.pasca-sidang')
                ->with('success', 'Sidang berhasil ditandai selesai.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menandai sidang selesai: ' . $e->getMessage());
        }
    }
}
