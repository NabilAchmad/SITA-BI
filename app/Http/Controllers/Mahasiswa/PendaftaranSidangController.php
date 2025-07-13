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

            // TODO: Implement the logic to save seminar proposal data here.

            return redirect()->route('mahasiswa.sidang.dashboard')
                ->with('alert', [
                    'title'   => 'Berhasil Disimpan!',
                    'message' => 'Draft proposal Anda telah berhasil disimpan. Silakan lanjutkan ke tahap berikutnya.',
                    'type'    => 'success',
                ]);
        } catch (\Exception $e) {
            // Catat error ke log untuk debugging.
            Log::error('Gagal menyimpan proposal: ' . $e->getMessage());

            // Kembalikan ke halaman sebelumnya dengan pesan error.
            return redirect()->back()
                ->with('alert', [
                    'title'   => 'Gagal!',
                    'message' => $e->getMessage(), // Tampilkan pesan error dari service
                    'type'    => 'error',
                ])
                ->withInput(); // Kembalikan input sebelumnya ke form
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
