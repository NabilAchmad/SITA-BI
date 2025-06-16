<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Sidang;
use App\Models\Jadwal;
use App\Models\JadwalSidang;
use App\Models\JudulTA;
use App\Models\Mahasiswa;
use App\Models\Nilai;

class KaprodiController extends Controller
{
    /**
     * Dashboard utama Kaprodi
     */
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    /**
     * Menampilkan seluruh jadwal sidang
     */
    public function showJadwal()
    {
        $jadwals = Jadwal::all();
        return view('kaprodi.jadwal.readJadwal', compact('jadwals'));
    }

    /**
     * Menampilkan daftar Judul TA yang masih berstatus "diajukan" (belum di‑ACC)
     */
    public function showJudulTA()
    {
        $judulTAs = JudulTA::where('status', 'diajukan')->get();
        return view('kaprodi.judulTA.AccJudulTA', compact('judulTAs'));
    }

    public function showAcc(){
        $judulTAs = JudulTA::where('status', 'disetujui')->get();
        return view('kaprodi.judulTA.readAcc', compact('judulTAs'));
    }

    public function showTolak(){
        $judulTolak = JudulTA::where('status', 'ditolak')->get();
        return view('kaprodi.judulTA.crud-JudulTA.readTolak', compact('judulTolak'));
    }

    /**
     * Daftar Nilai Sidang Akhir (read‑only untuk Kaprodi)
     */
    public function sidangAkhir()
    {
        $nilais = Nilai::with([
            'mahasiswa',        // relasi ke tabel mahasiswa
            'tugasAkhir',       // relasi ke tugas_akhir (jika ada)
            'dosenPenguji'      // relasi ke dosen penguji (jika ada)
        ])->get();

        return view('kaprodi.nilai.read', compact('nilais'));
    }

    /**
     * Menyimpan data sidang baru (jika Kaprodi membuat sidang manual)
     */
    public function storeSidang(Request $request)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nilai'   => 'nullable|numeric',
            'status'  => 'nullable|string|max:50',
        ]);

        Sidang::create($validated);

        return redirect()
            ->route('kaprodi.nilai.page')
            ->with('success', 'Sidang berhasil dibuat.');
    }

    /**
     * Menampilkan seluruh pengumuman
     */
    public function showPengumuman()
    {
        $pengumumans = Pengumuman::all();
        return view('kaprodi.Pengumuman.pengumuman', compact('pengumumans'));
    }

    /**
     * ACC Judul TA
     */
    public function approveJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status       = 'Disetujui';
        $judul->tanggal_acc  = now();
        $judul->save();

        return response()->json(['message' => 'Judul telah disetujui']);
    }

    /**
     * Menolak Judul TA
     */
    public function rejectJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judul->status = 'Ditolak';
        $judul->save();

        return response()->json(['message' => 'Judul telah ditolak']);
    }

    /**
     * Dashboard ringkasan Sidang
     */
    public function showSidangDashboard()
    {
        // Count mahasiswa waiting for Sidang Sempro scheduling
        $waitingSemproCount = Mahasiswa::whereDoesntHave('jadwalSidangSempro')->count();

        // Count mahasiswa waiting for Sidang Akhir scheduling
        $waitingAkhirCount = Mahasiswa::whereDoesntHave('jadwalSidangAkhir')->count();

        // Count scheduled Sidang Sempro
        $scheduledSemproCount = JadwalSidang::where('jenis_sidang', 'sempro')->count();

        // Count scheduled Sidang Akhir
        $scheduledAkhirCount = JadwalSidang::where('jenis_sidang', 'akhir')->count();

        // Count Pasca Sidang Sempro
        $pascaSemproCount = Sidang::where('jenis_sidang', 'sempro')->count();

        // Count Pasca Sidang Akhir
        $pascaAkhirCount = Sidang::where('jenis_sidang', 'akhir')->count();

        return view('kaprodi.sidang.dashboard.dashboard', compact(
            'waitingSemproCount',
            'waitingAkhirCount',
            'scheduledSemproCount',
            'scheduledAkhirCount',
            'pascaSemproCount',
            'pascaAkhirCount'
        ));
    }

    /**
     * Statistik jumlah mahasiswa yang akan sidang
     */
    public function showMahasiswaSidang()
    {
        $mahasiswaCount = Mahasiswa::count();
        return view('kaprodi.sidang.dashboard.dashboard', compact('mahasiswaCount'));
    }

    /**
     * Menampilkan hasil sidang sesuai jadwal yang dibuat admin
     */
    public function showSidangResults()
    {
        $jadwalSidangs = JadwalSidang::with([
            'sidang.nilai',
            'sidang.tugasAkhir.mahasiswa'
        ])->get();

        return view('kaprodi.sidang.read', compact('jadwalSidangs'));
    }

    /**
     * Mendapatkan judul-judul yang mirip berdasarkan id judul
     */
    public function getSimilarJudul($id)
    {
        $judul = JudulTA::findOrFail($id);
        $judulText = $judul->judul;

        // Pisahkan kata-kata dari judul, hapus kata-kata pendek (<= 3 huruf)
        $keywords = array_filter(preg_split('/\s+/', strtolower($judulText)), function ($word) {
            return strlen($word) > 3;
        });

        // Cari judul lain yang mengandung salah satu kata kunci
        $similarJuduls = JudulTA::where('id', '!=', $id)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('judul', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->get();

        // Siapkan data untuk response
        $data = [
            'nama_pengaju' => $judul->mahasiswa->nama ?? 'N/A',
            'judul_ta' => $judulText,
            'similar_juduls' => $similarJuduls->pluck('judul'),
        ];

        return response()->json($data);
    }

    /**
     * Menampilkan mahasiswa menunggu sidang akhir
     */
    public function showMahasiswaMenungguAkhir()
    {
        $mahasiswaMenunggu = Mahasiswa::whereDoesntHave('jadwalSidangAkhir')->paginate(10);
        return view('kaprodi.sidang.akhir.views.mhs-menunggu-akhir', compact('mahasiswaMenunggu'));
    }

    /**
     * Menampilkan mahasiswa menunggu sidang sempro
     */
    public function showMahasiswaSidangSempro()
    {
        $mahasiswaMenunggu = Mahasiswa::whereDoesntHave('jadwalSidangSempro')->paginate(10);
        $mahasiswaTidakLulus = Mahasiswa::whereHas('tugasAkhir', function ($query) {
            $query->where('status', 'tidak lulus');
        })->paginate(10);
        return view('kaprodi.sidang.sempro.views.mhs-sidang', compact('mahasiswaMenunggu', 'mahasiswaTidakLulus'));
    }

    /**
     * Menampilkan sidang sempro terjadwal
     */
    public function showSidangSemproTerjadwal()
    {
        $jadwalSempro = JadwalSidang::where('jenis_sidang', 'sempro')->paginate(10);
        return view('kaprodi.sidang.sempro.views.jadwal-sempro', compact('jadwalSempro'));
    }

    /**
     * Menampilkan sidang akhir terjadwal
     */
    public function showSidangAkhirTerjadwal()
    {
        $jadwalAkhir = JadwalSidang::where('jenis_sidang', 'akhir')->paginate(10);
        return view('kaprodi.sidang.akhir.views.jadwal-akhir', compact('jadwalAkhir'));
    }

    /**
     * Menampilkan pasca sidang sempro
     */
    public function showPascaSidangSempro()
    {
        $pascaSempro = Sidang::where('jenis_sidang', 'sempro')->paginate(10);
        return view('kaprodi.sidang.sempro.views.pasca-sempro', compact('pascaSempro'));
    }

    /**
     * Menampilkan pasca sidang akhir
     */
    public function showPascaSidangAkhir()
    {
        $pascaAkhir = Sidang::where('jenis_sidang', 'akhir')->paginate(10);
        return view('kaprodi.sidang.akhir.views.pasca-akhir', compact('pascaAkhir'));
    }
}
