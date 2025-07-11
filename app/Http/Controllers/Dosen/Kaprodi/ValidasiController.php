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
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ValidasiService $validasiService) {}

    public function index(): View
    {
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

    /**
     * [PERBAIKAN UTAMA DI SINI]
     * Mengambil detail Tugas Akhir dan mempersiapkan data untuk ditampilkan di frontend.
     */
     /**
     * [PERBAIKAN KUNCI DI SINI]
     * Menghapus pemanggilan ke service dan membangun array JSON langsung di controller.
     */
    public function getDetail(Request $request, TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
            $this->authorize('view', $tugasAkhir);

            // 1. Eager load semua relasi yang dibutuhkan dengan benar.
            $tugasAkhir->load(['mahasiswa.user', 'approver', 'rejecter']);

            // 2. [DIHAPUS] Tidak perlu lagi memanggil service untuk detail.
            // $details = $this->validasiService->getValidationDetails($tugasAkhir);

            // 3. [BARU] Buat array response langsung di sini. Ini menjadi satu-satunya sumber kebenaran.
            $details = [
                'nama' => $tugasAkhir->mahasiswa?->user?->name ?? 'N/A',
                'nim' => $tugasAkhir->mahasiswa?->nim ?? '-',
                'prodi' => $tugasAkhir->mahasiswa?->prodi ?? 'N/A',
                'judul' => $tugasAkhir->judul,

                // Gunakan relasi yang sudah benar ('approver' dan 'rejecter')
                'approver_name' => $tugasAkhir->approver?->name,
                'rejecter_name' => $tugasAkhir->rejecter?->name,
                
                // Format tanggal
                'formatted_approval_date' => $tugasAkhir->tanggal_disetujui?->translatedFormat('d F Y'),
                'formatted_rejection_date' => $tugasAkhir->tanggal_ditolak?->translatedFormat('d F Y'),
                'alasan_penolakan' => $tugasAkhir->alasan_penolakan,

                // Tentukan hak akses
                'actionable' => $tugasAkhir->status === 'diajukan' && $request->user()->can('update', $tugasAkhir),
                'can_check_similarity' => $request->user()->can('cekKemiripan', $tugasAkhir),
            ];

            return response()->json($details);

        } catch (\Exception $e) {
            Log::error("Gagal mengambil detail TA #{$tugasAkhir->id}: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            return response()->json(['error' => 'Tidak dapat mengambil detail data. Terjadi kesalahan pada server.'], 500);
        }
    }

    public function cekKemiripan(TugasAkhir $tugasAkhir): JsonResponse
    {
        try {
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
