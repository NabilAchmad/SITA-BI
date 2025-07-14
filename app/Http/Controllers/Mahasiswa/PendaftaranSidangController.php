<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk mencatat error

class PendaftaranSidangController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran seminar proposal.
     */
    public function form()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with('tugasAkhir.dosenPembimbing')
            ->firstOrFail();
        // Nama view bisa disesuaikan, 'create' lebih mengikuti konvensi RESTful
        return view('mahasiswa.sidang.views.form', compact('mahasiswa'));
    }

    /**
     * Menampilkan dashboard sidang.
     */
    public function dashboard()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        // Sesuaikan view dashboard sidang sesuai kebutuhan
        return view('mahasiswa.sidang.dashboard.dashboard', compact('mahasiswa'));
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
