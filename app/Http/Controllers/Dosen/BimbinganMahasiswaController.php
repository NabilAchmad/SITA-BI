<?php

namespace App\Http\Controllers\Dosen;

use App\Models\BimbinganTA;
use App\Models\HistoryPerubahanJadwal;
use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- DITAMBAHKAN

class BimbinganMahasiswaController extends Controller
{
    use AuthorizesRequests; // <-- DITAMBAHKAN

    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    /**
     * Menampilkan dasbor daftar mahasiswa bimbingan.
     * Metode ini sudah benar.
     */
    public function index(Request $request)
    {

        if ($request->input('mode') === 'pantau_semua' && auth()->user()->can('pantau-semua-bimbingan')) {
            $mahasiswaList = $this->bimbinganService->getAllBimbinganAktif($request);
        } else {
            $mahasiswaList = $this->bimbinganService->getPengajuanBimbingan($request);
        }


        // Pastikan path view ini benar sesuai struktur folder Anda
        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    /**
     * [PERBAIKAN UTAMA] Menampilkan halaman "Pusat Komando Bimbingan".
     * Metode ini sekarang menerima objek TugasAkhir secara langsung.
     */
    public function show(TugasAkhir $tugasAkhir)
    {
        try {
            $data = $this->bimbinganService->getDataForBimbinganDetailPage($tugasAkhir);
        } catch (UnauthorizedException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }

        // ✅ PERBAIKAN: Memastikan semua data dari service diteruskan ke view.
        return view('dosen.bimbingan.detail-bimbingan.detail', $data);
    }
}
