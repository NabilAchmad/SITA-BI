<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PendaftaranSidang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Mahasiswa\PendaftaranSidangRequest; // Pastikan namespace ini benar
use App\Services\Mahasiswa\PendaftaranSidangService;

class PendaftaranSidangController extends Controller
{
    protected $pendaftaranSidangService; // Deklarasi service

    public function __construct(PendaftaranSidangService $pendaftaranSidangService)
    {
        $this->pendaftaranSidangService = $pendaftaranSidangService;
    }

    /**
     * ✅ PERBAIKAN: Menampilkan form pendaftaran dengan pengecekan.
     */
    public function form()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with('tugasAkhir')
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

        return view('mahasiswa.sidang.views.form', compact('mahasiswa'));
    }

    /**
     * ✅ PERBAIKAN: Menampilkan dashboard dengan data pendaftaran terakhir.
     */
    public function dashboard()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        // Mengambil pendaftaran terakhir untuk ditampilkan di dashboard
        $pendaftaranTerbaru = PendaftaranSidang::whereHas('tugasAkhir', function ($query) use ($mahasiswa) {
            $query->where('mahasiswa_id', $mahasiswa->id);
        })->latest()->first();

        return view('mahasiswa.sidang.dashboard.dashboard', compact('mahasiswa', 'pendaftaranTerbaru'));
    }

    /**
     * Menyimpan data pendaftaran sidang (sudah benar).
     */
    public function store(PendaftaranSidangRequest $request)
    {
        try {
            $this->pendaftaranSidangService->handleRegistration($request);

            return redirect()->route('mahasiswa.sidang.dashboard')
                ->with('alert', [
                    'title'   => 'Berhasil!',
                    'message' => 'Pendaftaran sidang berhasil dikirim. Silakan tunggu verifikasi dari dosen pembimbing.',
                    'type'    => 'success',
                ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pendaftaran sidang: ' . $e->getMessage());
            return redirect()->back()->with('alert', [
                'title'   => 'Gagal!',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'type'    => 'error',
            ])->withInput();
        }
    }

    /**
     * ✅ PERBAIKAN: Menampilkan nilai sidang dengan query yang lebih efisien.
     */
    public function nilaiSidang()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with(['tugasAkhir.sidang.nilaiSidang.dosen.user']) // Muat relasi yang diperlukan
            ->firstOrFail();

        // Ambil koleksi sidang jika tugas akhir ada
        $sidangs = $mahasiswa->tugasAkhir ? $mahasiswa->tugasAkhir->sidang : collect();

        return view('mahasiswa.sidang.views.nilaiSidang', compact('sidangs', 'mahasiswa'));
    }

    /**
     * ✅ PERBAIKAN: Menampilkan jadwal sidang dengan query yang lebih sederhana.
     */
    public function jadwalSidang()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())
            ->with(['tugasAkhir.sidang' => function ($query) {
                // Eager load relasi jadwalSidang hanya untuk sidang yang relevan
                $query->with('jadwalSidang.ruangan');
            }])
            ->firstOrFail();

        $sidangs = $mahasiswa->tugasAkhir ? $mahasiswa->tugasAkhir->sidang : collect();

        return view('mahasiswa.sidang.views.jadwal', compact('sidangs'));
    }
}
