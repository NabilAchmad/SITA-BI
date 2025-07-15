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
        $mahasiswaList = $this->bimbinganService->getPengajuanBimbingan($request);

        // Pastikan path view ini benar sesuai struktur folder Anda
        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    public function show(TugasAkhir $tugasAkhir)
    {
        try {
            // Baris ini sekarang akan berfungsi dengan benar
            $this->authorize('view', $tugasAkhir);

            $data = $this->bimbinganService->getDataForBimbinganDetailPage($tugasAkhir);
        } catch (UnauthorizedException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }

        // ✅ PERBAIKAN: Memastikan semua data dari service diteruskan ke view.
        return view('dosen.bimbingan.detail-bimbingan.detail', [
            'mahasiswa'        => $tugasAkhir->mahasiswa,
            'tugasAkhir'       => $data['tugasAkhir'],
            'sesiAktif'        => $data['sesiAktif'],
            'catatanList'      => $data['catatanList'],
            'bimbinganCountP1' => $data['bimbinganCountP1'],
            'bimbinganCountP2' => $data['bimbinganCountP2'],
            'dokumenTerbaru'   => $data['dokumenTerbaru'],
            'riwayatDokumen'   => $data['riwayatDokumen'],
            'pembimbing1'      => $data['pembimbing1'],
            'pembimbing2'      => $data['pembimbing2'],
        ]);
    }
}
