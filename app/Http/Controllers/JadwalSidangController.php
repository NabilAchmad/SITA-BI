<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\PeranDosenTA;
use Illuminate\Support\Facades\DB;

class JadwalSidangController extends Controller
{

    public function index()
    {
        // JadwalSidangController@index misalnya
        $jadwalList = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->get()->unique(fn($item) => $item->sidang->tugasAkhir->mahasiswa_id);

        return view('admin.sidang.jadwal.views.readJadwalSidang', compact('jadwalList'));
    }

    public function create(Request $request)
    {
        $sidangId = $request->sidang_id;
        $sidang = Sidang::with('tugasAkhir.mahasiswa.user')->findOrFail($sidangId);
        $ruanganList = Ruangan::all();
        $dosenList = Dosen::with('user')->get();

        return view('admin.sidang.jadwal.views.createJadwalSidang', compact('sidang', 'ruanganList', 'dosenList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sidang_id' => 'required|exists:sidang,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'ruangan_id' => 'required|exists:ruangan,id',
        ]);

        JadwalSidang::create([
            'sidang_id' => $request->sidang_id,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'ruangan_id' => $request->ruangan_id,
        ]);

        return redirect()->route('jadwal-sidang.read')->with('success', 'Jadwal sidang berhasil dibuat.');
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
            // Tentukan peran berdasarkan index: 0->penguji1, 1->penguji2, 2->penguji3
            $peran = 'penguji' . ($index + 1);

            PeranDosenTA::create([
                'dosen_id' => $dosenId,
                'tugas_akhir_id' => $sidang_id,
                'peran' => $peran,
            ]);
        }

        return redirect()->route('jadwal-sidang.create', ['sidang_id' => $sidang_id])
            ->with('success', 'Dosen penguji berhasil disimpan.');
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

        return view('admin.sidang.jadwal.views.detail', compact('jadwal', 'dosens', 'ruangans'));
    }

    public function pascaSidang()
    {
        $jadwalList = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->get()->unique(fn($item) => $item->sidang->tugasAkhir->mahasiswa_id);

        return view('admin.sidang.jadwal.views.pasca', compact('jadwalList'));
    }

    public function tandaiSidang($sidang_id)
    {
        try {
            // Ambil sidang beserta relasi tugasAkhir dan mahasiswa-nya
            $sidang = Sidang::with('tugasAkhir.mahasiswa')->findOrFail($sidang_id);

            // Update status jadi selesai
            $sidang->status = 'selesai';
            $sidang->save();

            // Data mahasiswa terkait sidang
            $mahasiswa = $sidang->tugasAkhir->mahasiswa;

            return response()->json([
                'success' => true,
                'message' => 'Sidang berhasil ditandai selesai.',
                'sidang' => $sidang,
                'mahasiswa' => $mahasiswa,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai sidang selesai: ' . $e->getMessage(),
            ], 500);
        }
    }
}
