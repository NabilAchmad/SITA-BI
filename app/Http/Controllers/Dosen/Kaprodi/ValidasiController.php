<?php

namespace App\Http\Controllers\Dosen\Kaprodi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kaprodi\RejectTugasAkhirRequest;
use App\Models\TugasAkhir;
use App\Services\Kaprodi\ValidasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    protected ValidasiService $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        $this->validasiService = $validasiService;
    }

    /**
     * Menampilkan daftar tugas akhir yang perlu divalidasi.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // Ambil data untuk setiap tab secara terpisah
        $tugasAkhirMenunggu = TugasAkhir::with('mahasiswa.user')
            ->where('status', TugasAkhir::STATUS_DIAJUKAN)
            ->latest()
            ->get();

        $tugasAkhirDiterima = TugasAkhir::with('mahasiswa.user')
            ->where('status', TugasAkhir::STATUS_DISETUJUI)
            ->latest()
            ->get();

        $tugasAkhirDitolak = TugasAkhir::with('mahasiswa.user')
            ->where('status', TugasAkhir::STATUS_DITOLAK)
            ->latest()
            ->get();

        // Kirim ketiga variabel tersebut ke view menggunakan compact
        return view('dosen.kaprodi.validasi.index', compact(
            'tugasAkhirMenunggu',
            'tugasAkhirDiterima',
            'tugasAkhirDitolak'
        ));
    }

    /**
     * PERBAIKAN: Metode baru untuk menangani AJAX/Fetch request dari modal.
     * Metode ini akan mengembalikan data dalam format JSON.
     *
     * @param TugasAkhir $tugasAkhir
     * @return JsonResponse
     */
    public function getDetail(TugasAkhir $tugasAkhir): JsonResponse
    {
        // Panggil service untuk mendapatkan data yang sudah diformat
        $details = $this->validasiService->getValidationDetails($tugasAkhir);

        // Kembalikan sebagai respons JSON
        return response()->json($details);
    }

    /**
     * Menyetujui pengajuan tugas akhir.
     * Nama metode diubah dari 'approve' menjadi 'terima' agar sesuai dengan route di view.
     *
     * @param TugasAkhir $tugasAkhir
     * @return RedirectResponse
     */
    public function terima(TugasAkhir $tugasAkhir): RedirectResponse
    {
        $this->validasiService->approveTugasAkhir($tugasAkhir);

        // Menggunakan format notifikasi yang konsisten
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tugas akhir telah diterima.'
        ]);
    }

    /**
     * Menolak pengajuan tugas akhir.
     *
     * @param RejectTugasAkhirRequest $request
     * @param TugasAkhir $tugasAkhir
     * @return RedirectResponse
     */
    public function tolak(RejectTugasAkhirRequest $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        // Panggil service untuk menolak TA, teruskan alasan penolakan dari request.
        $this->validasiService->rejectTugasAkhir(
            $tugasAkhir,
            $request->input('alasan_penolakan') // Input ini harus cocok dengan name di form view.
        );

        return redirect()->back()->with('alert', [
            'type' => 'success', // Gunakan 'success' atau 'error' untuk sweet alert
            'title' => 'Berhasil',
            'message' => 'Pengajuan tugas akhir mahasiswa telah ditolak.'
        ]);
    }

    // CATATAN: Metode show() dan detailPengajuan() yang lama bisa dihapus
    // karena fungsionalitasnya sudah digantikan oleh getDetail() yang lebih efisien.
}
