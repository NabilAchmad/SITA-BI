<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Mahasiswa\PendaftaranSidangRequest; // Pastikan namespace ini benar
use App\Services\Mahasiswa\PendaftaranSidangService;
use App\Models\JadwalSidang;
use App\Models\Sidang;
use App\Models\PendaftaranSidang;

class PendaftaranSidangController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran seminar proposal.
     */
    
    // public function form()
    public function form()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with('tugasAkhir.dosenPembimbing')
            ->firstOrFail();

        // Pengecekan: Apakah mahasiswa sudah memiliki pendaftaran yang sedang diproses?
        $pendaftaranAktif = PendaftaranSidang::where('tugas_akhir_id', $mahasiswa->tugasAkhir->id)
            ->whereIn('status_verifikasi', ['menunggu_verifikasi']) // Sesuaikan statusnya
            ->exists();

        if ($pendaftaranAktif) {
            return redirect()->route('mahasiswa.sidang.dashboard')->with('alert', [
                'title'   => 'Informasi',
                'message' => 'Anda sudah memiliki pendaftaran sidang yang sedang diproses.',
                'type'    => 'info',
            ]);
        }

        return view('mahasiswa.sidang.dashboard.form', compact('mahasiswa'));
    }

    /**
     * Menampilkan dashboard sidang.
     */
    public function dashboard()
    {
        // 1. Dapatkan data mahasiswa yang sedang login
        $mahasiswa = Auth::user()->mahasiswa;

        // 2. Inisialisasi variabel yang akan dikirim ke view
        $jadwal = null;
        $sidang = null;
        $nilaiAkhir = 0;

        // 3. Lanjutkan hanya jika user adalah mahasiswa & memiliki tugas akhir
        if ($mahasiswa && $mahasiswa->tugasAkhir) {

            // 4. Query utama: Cari jadwal sidang yang relevan
            $jadwal = JadwalSidang::with([
                // Muat relasi untuk Tab 1 (Jadwal Sidang)
                'ruangan',
                'sidang.tugasAkhir.peranDosenTa' => function ($query) {
                    $query->where('peran', 'like', 'penguji%')->with('dosen.user');
                },

                // Muat relasi untuk Tab 2 (Nilai Sidang)
                'sidang.nilaiSidang.dosen.user'

            ])->whereHas('sidang', function ($query) use ($mahasiswa) {
                $query->where('tugas_akhir_id', $mahasiswa->tugasAkhir->id);
                // Anda bisa tambahkan filter status jika perlu, misal: ->where('status_hasil', 'dijadwalkan')
            })
                ->latest() // Ambil jadwal yang paling baru
                ->first();

            // 5. Jika jadwal ditemukan, siapkan data untuk Tab 2
            if ($jadwal) {
                // Ambil objek sidang dari jadwal yang sudah di-load
                $sidang = $jadwal->sidang;

                // Lakukan kalkulasi hanya jika ada nilai yang sudah masuk
                if ($sidang && $sidang->nilaiSidang->isNotEmpty()) {
                    $totalSkor = $sidang->nilaiSidang->sum('skor');
                    $nilaiAkhir = $totalSkor * 0.25; // Rumus: total skor x 25%
                }
            }
        }

        // 6. Kirim semua variabel yang dibutuhkan oleh kedua tab ke view
        return view('mahasiswa.sidang.dashboard.dashboard', compact('jadwal', 'sidang', 'nilaiAkhir'));
    }

    /**
     * Menyimpan data pendaftaran seminar proposal.
     */
    public function store(Request $request)
    {
        try {
            $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

            $request->validate([
                'judul_ta' => 'required|string|max:255',
                'jumlah_bimbingan' => 'required|integer|min:7',
                'file_ta' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10MB
            ]);

            if ($request->input('jumlah_bimbingan') < 7) {
                return redirect()->back()->with('alert', [
                    'title' => 'Gagal!',
                    'message' => 'Jumlah bimbingan minimal 7 kali untuk mendaftar sidang akhir.',
                    'type' => 'error',
                ])->withInput();
            }

            // Simpan file tugas akhir
            $filePath = $request->file('file_ta')->store('final_documents', 'public');

            // Buat record sidang baru dengan status menunggu_verifikasi
            $sidang = $mahasiswa->tugasAkhir->sidang()->create([
                'judul' => $request->input('judul_ta'),
                'status' => 'menunggu_verifikasi',
                'file_path' => $filePath,
                'jenis_sidang' => 'akhir',
            ]);

            return redirect()->route('mahasiswa.sidang.dashboard')
                ->with('alert', [
                    'title' => 'Berhasil!',
                    'message' => 'Pendaftaran sidang akhir berhasil, menunggu verifikasi dosen pembimbing.',
                    'type' => 'success',
                ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pendaftaran sidang akhir: ' . $e->getMessage());

            return redirect()->back()
                ->with('alert', [
                    'title' => 'Gagal!',
                    'message' => $e->getMessage(),
                    'type' => 'error',
                ])
                ->withInput();
        }
    }

    /**
     * Menampilkan halaman nilai sidang untuk mahasiswa.
     */
    public function nilaiSidang()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with(['tugasAkhir.sidang.nilaiSidang.dosen', 'tugasAkhir.mahasiswa.user'])
            ->firstOrFail();

        $sidang = $mahasiswa->tugasAkhir ? $mahasiswa->tugasAkhir->sidang : collect();

        return view('mahasiswa.sidang.views.nilaiSidang', compact('sidang', 'mahasiswa'));
    }

    /**
     * Menampilkan jadwal sidang yang sudah diberikan oleh admin.
     */
    public function jadwalSidang()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with(['tugasAkhir.sidang.jadwalSidang.ruangan', 'tugasAkhir.sidang.nilaiSidang.dosen.user'])
            ->firstOrFail();

        $sidangs = $mahasiswa->tugasAkhir ? $mahasiswa->tugasAkhir->sidang : collect();

        return view('mahasiswa.sidang.views.jadwal', compact('sidangs'));
    }
}
