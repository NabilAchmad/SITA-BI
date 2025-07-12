<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class BimbinganMahasiswaController extends Controller
{
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
        $mahasiswaList = $this->bimbinganService->getPengajuanBimbingan($request);

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
            // Service akan menangani otorisasi dan pengambilan semua data yang dibutuhkan
            $data = $this->bimbinganService->getDataForBimbinganDetailPage($tugasAkhir);
        } catch (UnauthorizedException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }

        // Mengirim semua data yang dibutuhkan oleh view baru yang telah kita rancang.
        // Pastikan path view ini benar.
        return view('dosen.bimbingan.detail-bimbingan.detail', [
            'mahasiswa' => $tugasAkhir->mahasiswa,
            'tugasAkhir' => $tugasAkhir,
            'catatanList' => $data['catatanList'],
            'bimbinganCountP1' => $data['bimbinganCountP1'],
            'bimbinganCountP2' => $data['bimbinganCountP2'],
            'pembimbing1' => $data['pembimbing1'],
            'pembimbing2' => $data['pembimbing2'],
        ]);
    }
}
