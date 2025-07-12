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
        // Service akan mengambil semua data yang dibutuhkan
        $data = $this->tugasAkhirService->getProgressPageData();

        // Jika tidak ada data TA aktif, tampilkan view khusus untuk itu
        if (!$data['tugasAkhir']) {
            return view('mahasiswa.tugas-akhir.partials._progress_empty');
        }

        // Kirim semua data yang sudah disiapkan oleh Service ke satu view progress yang utama
        return view('mahasiswa.tugas-akhir.crud-ta.progress', [
            'tugasAkhir'       => $data['tugasAkhir'],
            'catatanList'      => $data['catatanList'],
            'bimbinganCountP1' => $data['bimbinganCountP1'],
            'bimbinganCountP2' => $data['bimbinganCountP2'],
            'pembimbing1'      => $data['pembimbing1'],
            'pembimbing2'      => $data['pembimbing2'],
        ]);
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
        // Validasi sudah ditangani oleh UploadFileRequest
        $this->tugasAkhirService->handleUploadFile(
            $tugasAkhir,
            $request->file('file'),
            $request->input('jenis_dokumen'),
            $request->input('catatan') // ✅ Tambahkan parameter catatan
        );

        // DIUBAH: Menggunakan format notifikasi baru
        return redirect()->route('mahasiswa.tugas-akhir.progress')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'File tugas akhir Anda telah berhasil diunggah.'
        ]);
    }

    /**
     * Mengajukan pembatalan Tugas Akhir.
     */
    public function cancel(Request $request, TugasAkhir $tugasAkhir)
    {
        try {
            $this->tugasAkhirService->requestCancellation($tugasAkhir, $request->input('alasan'));

            // DIUBAH: Menggunakan format notifikasi baru
            return redirect()->route('mahasiswa.tugas-akhir.progress')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Pengajuan pembatalan Tugas Akhir telah dikirim.'
            ]);
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
