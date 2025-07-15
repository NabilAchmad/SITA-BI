<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Mahasiswa\TugasAkhirService;
use App\Http\Requests\Mahasiswa\CreateTugasAkhirRequest;
use App\Http\Requests\Mahasiswa\UploadFileRequest;
use Illuminate\Http\Request;

class TugasAkhirController extends Controller
{
    // Menggunakan constructor property promotion untuk kode yang lebih ringkas.
    public function __construct(protected TugasAkhirService $tugasAkhirService) {}

    public function dashboard()
    {
        $data = $this->tugasAkhirService->getDashboardData();
        return view('mahasiswa.tugas-akhir.dashboard.dashboard', $data);
    }

    public function progress()
    {
        $data = $this->tugasAkhirService->getProgressPageData();

        if (!$data['tugasAkhir']) {
            return view('mahasiswa.tugas-akhir.partials._progress_empty');
        }

        return view('mahasiswa.tugas-akhir.crud-ta.progress', [
            'tugasAkhir'       => $data['tugasAkhir'],
            'riwayatDokumen'   => $data['riwayatDokumen'], // <-- Variabel yang hilang ditambahkan
            'dokumenTerbaru'   => $data['dokumenTerbaru'], // <-- Variabel yang hilang ditambahkan
            'catatanList'      => $data['catatanList'],
            'bimbinganCountP1' => $data['bimbinganCountP1'],
            'bimbinganCountP2' => $data['bimbinganCountP2'],
            'pembimbing1'      => $data['pembimbing1'],
            'pembimbing2'      => $data['pembimbing2'],
            'jadwalAktif' => $data['jadwalAktif'],
            'isEligibleForRegistration' => $data['isEligibleForRegistration'], // <-- Variabel baru ditambahkan
            'mahasiswa' => $data['mahasiswa'], // <-- Variabel mahasiswa ditambahkan
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
     * [DIREVISI DENGAN DEBUGGING] Mengunggah file revisi sekaligus mengajukan sesi bimbingan baru.
     */
    public function ajukanBimbingan(UploadFileRequest $request, TugasAkhir $tugasAkhir)
    {
        try {
            $this->tugasAkhirService->ajukanBimbinganDenganFile(
                $tugasAkhir,
                $request->file('file_bimbingan'),
                $request->input('tipe_dokumen'),
                $request->input('catatan')
            );

            return redirect()->route('mahasiswa.tugas-akhir.progress')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'File berhasil diunggah dan pengajuan bimbingan terkirim.'
            ]);
        } catch (\Throwable $e) { // Menggunakan \Throwable untuk menangkap semua jenis error

            // ✅ PERBAIKAN UTAMA: Tampilkan error yang sebenarnya terjadi di Service sebagai JSON.
            // Ini akan menghentikan redirect dan memaksa browser menampilkan pesan error.
            return response()->json([
                'error' => 'Terjadi kesalahan internal pada server.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Mengajukan pembatalan Tugas Akhir.
     */
    public function cancel(Request $request, TugasAkhir $tugasAkhir)
    {
        try {
            $this->tugasAkhirService->requestCancellation($tugasAkhir, $request->input('alasan'));

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
