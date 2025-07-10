<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Mahasiswa\TugasAkhirService; // ✅ Menggunakan Service baru
use App\Http\Requests\Mahasiswa\CreateTugasAkhirRequest; // ✅ Menggunakan Request baru untuk 'store'
use App\Http\Requests\Mahasiswa\UploadFileRequest;
use Illuminate\Http\Request;

class TugasAkhirController extends Controller
{
    // Gunakan constructor property promotion untuk kode yang lebih ringkas
    public function __construct(protected TugasAkhirService $tugasAkhirService) {}

    public function dashboard()
    {
        $data = $this->tugasAkhirService->getDashboardData();
        return view('mahasiswa.tugas-akhir.dashboard.dashboard', $data);
    }

    public function progress()
    {
        $data = $this->tugasAkhirService->getProgressPageData();
        return view('mahasiswa.tugas-akhir.crud-ta.progress', $data);
    }

    /**
     * Menampilkan form untuk mengajukan TA mandiri.
     */
    public function ajukanForm()
    {
        return view('mahasiswa.tugas-akhir.crud-ta.create');
    }

    /**
     * Menyimpan pengajuan TA mandiri baru.
     */
    public function store(CreateTugasAkhirRequest $request)
    {
        try {
            $this->tugasAkhirService->createTugasAkhir($request->validated());
            return redirect()->route('mahasiswa.tugas-akhir.dashboard')->with('success', 'Tugas Akhir berhasil diajukan!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Mengunggah file tugas akhir.
     */
    public function uploadFile(UploadFileRequest $request, TugasAkhir $tugasAkhir)
    {
        // Asumsi jenis dokumen dikirim dari form, atau default 'lainnya'
        $jenisDokumen = $request->input('jenis_dokumen', 'lainnya');

        $this->tugasAkhirService->handleUploadFile($tugasAkhir, $request->file('file'), $jenisDokumen);

        return redirect()->route('mahasiswa.tugas-akhir.progress')->with('success', 'File tugas akhir Anda telah berhasil diunggah.');
    }

    /**
     * Mengajukan pembatalan Tugas Akhir.
     */
    public function cancel(Request $request, TugasAkhir $tugasAkhir)
    {
        try {
            $this->tugasAkhirService->requestCancellation($tugasAkhir, $request->input('alasan'));
            return redirect()->route('mahasiswa.tugas-akhir.progress')->with('success', 'Pengajuan pembatalan Tugas Akhir telah dikirim.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman riwayat TA yang dibatalkan.
     */
    public function showCancelled()
    {
        $tugasAkhirDibatalkan = $this->tugasAkhirService->getCancelledTugasAkhir();
        return view('mahasiswa.tugas-akhir.crud-ta.cancel', compact('tugasAkhirDibatalkan'));
    }
}
