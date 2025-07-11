<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;

class BimbinganMahasiswaController extends Controller
{
    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    /**
     * Menampilkan dasbor daftar mahasiswa bimbingan.
     */
    public function index(Request $request)
    {
        $mahasiswaList = $this->bimbinganService->getFilteredMahasiswaBimbingan($request);

        // Pastikan path view ini benar
        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    /**
     * Menampilkan halaman "Pusat Komando Bimbingan" untuk satu tugas akhir.
     */
    public function show(TugasAkhir $tugasAkhir)
    {
        try {
            // Service akan menangani otorisasi dan pengambilan data
            $data = $this->bimbinganService->getDataForBimbinganDetailPage($tugasAkhir);
        } catch (\Illuminate\Validation\UnauthorizedException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }

        // Mengirim semua data yang dibutuhkan oleh view baru
        return view('dosen.bimbingan.detail-bimbingan.detail', [
            'mahasiswa' => $tugasAkhir->mahasiswa,
            'tugasAkhir' => $tugasAkhir,
            'catatanList' => $data['catatanList'],
            'bimbinganCount' => $data['bimbinganCount'],
        ]);
    }
}
