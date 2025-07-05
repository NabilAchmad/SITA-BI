<?php

namespace App\Http\Controllers\Dosen\Kaprodi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kaprodi\RejectTugasAkhirRequest;
use App\Models\TugasAkhir;
use App\Services\Kaprodi\ValidasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ValidasiController extends Controller
{
    use AuthorizesRequests;
    protected ValidasiService $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        $this->validasiService = $validasiService;
    }

    public function index(): View
    {
        try {
            $data = $this->validasiService->getValidationLists();
            return view('dosen.kaprodi.validasi.index', $data);
        } catch (\Exception $e) {
            Log::error('Gagal memuat halaman validasi Kaprodi: ' . $e->getMessage());
            return view('dosen.kaprodi.validasi.index', [
                'tugasAkhirMenunggu' => collect(),
                'tugasAkhirDiterima' => collect(),
                'tugasAkhirDitolak' => collect(),
            ])->with('error', 'Gagal memuat data dari server. Silakan hubungi administrator.');
        }
    }

    public function getDetail(TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
            $this->authorize('view', $tugasAkhir);
            $tugasAkhir->load(['mahasiswa.user', 'approver', 'rejector']); // Eager load relasi yang benar
            $details = $this->validasiService->getValidationDetails($tugasAkhir);
            return response()->json($details);
        } catch (\Exception $e) {
            Log::error("Gagal mengambil detail TA #{$tugasAkhir->id}: " . $e->getMessage());
            return response()->json(['error' => 'Tidak dapat mengambil detail data. Terjadi kesalahan pada server.'], 500);
        }
    }

    public function cekKemiripan(TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
            $this->authorize('view', $tugasAkhir);
            $hasil = $this->validasiService->cekKemiripanJudulCerdas($tugasAkhir);
            return response()->json($hasil);
        } catch (\Exception $e) {
            Log::error("Gagal cek kemiripan TA #{$tugasAkhir->id}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal melakukan pengecekan kemiripan. Silakan coba lagi.'], 500);
        }
    }

    public function terima(TugasAkhir $tugasAkhir): RedirectResponse
    {
        try {
            $this->authorize('update', $tugasAkhir);
            $this->validasiService->approveTugasAkhir($tugasAkhir);
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Tugas akhir telah diterima.'
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal menyetujui TA #{$tugasAkhir->id}: " . $e->getMessage());
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat memproses data.'
            ]);
        }
    }

    public function tolak(RejectTugasAkhirRequest $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        try {
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
        } catch (\Exception $e) {
            Log::error("Gagal menolak TA #{$tugasAkhir->id}: " . $e->getMessage());
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat memproses data.'
            ]);
        }
    }
}
