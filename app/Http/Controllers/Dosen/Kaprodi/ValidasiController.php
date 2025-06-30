<?php

namespace App\Http\Controllers\Dosen\Kaprodi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kaprodi\RejectTugasAkhirRequest;
use App\Models\TugasAkhir;
use App\Services\Kaprodi\ValidasiService;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    protected ValidasiService $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        // PERBAIKAN: Middleware telah dipindahkan ke file routes/web.php
        // untuk praktik terbaik dan untuk menghilangkan peringatan dari Intelephense.
        $this->validasiService = $validasiService;
    }

    /**
     * Menampilkan daftar tugas akhir yang perlu divalidasi.
     */
    public function index(Request $request)
    {
        $tugasAkhir = $this->validasiService->getTugasAkhirForValidation($request);
        return view('dosen.kaprodi.validasi.index', compact('tugasAkhir'));
    }

    /**
     * Menampilkan detail tugas akhir untuk divalidasi.
     */
    public function show(TugasAkhir $tugasAkhir)
    {
        // Memuat relasi yang dibutuhkan oleh view detail
        $tugasAkhir->load('mahasiswa.user', 'revisiTa.pemberiRevisi');
        return view('dosen.kaprodi.validasi.show', compact('tugasAkhir'));
    }

    /**
     * Menyetujui pengajuan tugas akhir.
     */
    public function approve(TugasAkhir $tugasAkhir)
    {
        $this->validasiService->approveTugasAkhir($tugasAkhir);

        return redirect()->route('dosen.validasi.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tugas akhir telah disetujui.'
        ]);
    }

    /**
     * Menolak pengajuan tugas akhir.
     */
    public function reject(RejectTugasAkhirRequest $request, TugasAkhir $tugasAkhir)
    {
        $this->validasiService->rejectTugasAkhir($tugasAkhir, $request->input('catatan'));

        return redirect()->route('dosen.validasi.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tugas akhir telah ditolak dan catatan revisi telah dikirim.'
        ]);
    }
}
