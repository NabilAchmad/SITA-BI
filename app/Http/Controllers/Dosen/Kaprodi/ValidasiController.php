<?php

namespace App\Http\Controllers\Dosen\Kaprodi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kaprodi\RejectTugasAkhirRequest;
use App\Models\TugasAkhir;
use App\Services\Kaprodi\ValidasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    protected ValidasiService $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        $this->validasiService = $validasiService;
    }

    /**
     * Menampilkan halaman validasi dengan data yang sudah difilter
     * berdasarkan prodi Kaprodi yang login.
     */
    public function index(): View
    {
        $data = $this->validasiService->getValidationLists();

        return view('dosen.kaprodi.validasi.index', $data);
    }

    /**
     * Mengambil detail Tugas Akhir untuk ditampilkan di modal.
     */
    public function getDetail(TugasAkhir $tugasAkhir): JsonResponse
    {
        // Pastikan Kaprodi hanya bisa melihat detail dari prodinya sendiri
        $this->authorize('view', $tugasAkhir);

        $details = $this->validasiService->getValidationDetails($tugasAkhir);
        return response()->json($details);
    }

    /**
     * Menyetujui pengajuan tugas akhir.
     */
    public function terima(TugasAkhir $tugasAkhir): RedirectResponse
    {
        $this->authorize('update', $tugasAkhir);

        $this->validasiService->approveTugasAkhir($tugasAkhir);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Tugas akhir telah diterima.'
        ]);
    }

    /**
     * Menolak pengajuan tugas akhir.
     */
    public function tolak(RejectTugasAkhirRequest $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        // Otorisasi sudah ditangani oleh RejectTugasAkhirRequest dan policy
        $this->authorize('update', $tugasAkhir);

        $this->validasiService->rejectTugasAkhir(
            $tugasAkhir,
            $request->validated()['alasan_penolakan']
        );

        return redirect()->back()->with('alert', [
            'type' => 'warning',
            'title' => 'Ditolak!',
            'message' => 'Pengajuan tugas akhir telah ditolak.'
        ]);
    }
}
