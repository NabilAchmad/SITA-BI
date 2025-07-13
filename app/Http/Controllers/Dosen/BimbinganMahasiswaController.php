<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\BimbinganTA;
use App\Models\HistoryPerubahanJadwal;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BimbinganMahasiswaController extends Controller
{
    protected BimbinganService $bimbinganService;
    use AuthorizesRequests;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    public function index(Request $request)
    {
        if ($request->input('mode') === 'pantau_semua' && auth()->user()->can('pantau-semua-bimbingan')) {
            $mahasiswaList = $this->bimbinganService->getAllBimbinganAktif($request);
        } else {
            $mahasiswaList = $this->bimbinganService->getPengajuanBimbingan($request);
        }

        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    public function show(TugasAkhir $tugasAkhir, Request $request)
    {
        try {
            $isPantauSemuaMode = $request->input('mode') === 'pantau_semua' && auth()->user()->can('pantau-semua-bimbingan');

            if (!$isPantauSemuaMode) {
                $this->authorize('view', $tugasAkhir);
            }

            $data = $this->bimbinganService->getDataForBimbinganDetailPage($tugasAkhir);

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
        } catch (AuthorizationException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }
    }
}
