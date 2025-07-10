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
use Illuminate\Http\Request; // <-- Jangan lupa tambahkan ini jika belum ada

class ValidasiController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ValidasiService $validasiService)
    {
        // Konstruktor dibiarkan kosong, otorisasi ditangani per-metode.
    }

    public function index(): View
    {
        // ✅ PERBAIKAN: Tambahkan otorisasi untuk melihat halaman utama.
        $this->authorize('viewAny', TugasAkhir::class);

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



    public function getDetail(Request $request, TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
            $this->authorize('view', $tugasAkhir);

            $tugasAkhir->load(['mahasiswa.user', 'disetujui_oleh', 'ditolak_oleh']);

            // 1. Ambil data mentah dari service
            $details = $this->validasiService->getValidationDetails($tugasAkhir);

            // 2. ✅ Tentukan hak akses secara terpisah
            // 'actionable' HANYA untuk tombol Setujui/Tolak (berdasarkan policy 'update')
            $details['actionable'] = $tugasAkhir->status === 'diajukan' && $request->user()->can('update', $tugasAkhir);

            // 'can_check_similarity' untuk tombol Cek Kemiripan (berdasarkan policy 'cekKemiripan')
            $details['can_check_similarity'] = $request->user()->can('cekKemiripan', $tugasAkhir);

            // 3. Kirim data lengkap ke front-end
            return response()->json($details);
        } catch (\Exception $e) {
            Log::error("Gagal mengambil detail TA #{$tugasAkhir->id}: " . $e->getMessage());
            return response()->json(['error' => 'Tidak dapat mengambil detail data. Terjadi kesalahan pada server.'], 500);
        }
    }

    public function cekKemiripan(TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
            // ✅ PERBAIKAN: Panggil metode policy 'cekKemiripan' yang sudah dibuat.
            $this->authorize('cekKemiripan', $tugasAkhir);

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
            // Otorisasi ini sudah benar.
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
            // Otorisasi ini sudah benar.
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
