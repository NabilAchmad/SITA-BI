<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\BimbinganTA;
use App\Models\HistoryPerubahanJadwal;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;

class BimbinganMahasiswaController extends Controller
{
    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    /**
     * Menampilkan dashboard dengan daftar mahasiswa bimbingan yang sudah difilter.
     */
    public function dashboard(Request $request)
    {
        $mahasiswaList = $this->bimbinganService->getFilteredMahasiswaBimbingan($request);
        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    /**
     * Menampilkan halaman detail bimbingan untuk seorang mahasiswa.
     * Laravel akan otomatis menemukan TugasAkhir berdasarkan {id} di URL.
     */
    public function showDetail(int $mahasiswaId) // Route Model Binding
    {
        try {
            // Service akan menangani otorisasi dan memuat semua data yang dibutuhkan.
            $dataTugasAkhir = $this->bimbinganService->getTugasAkhirDetailForMahasiswa($mahasiswaId);

            return view('dosen.bimbingan.detail-bimbingan.detail', [
                'mahasiswa'     => $dataTugasAkhir->mahasiswa,
                'tugasAkhir'    => $dataTugasAkhir,
                'bimbinganList' => $dataTugasAkhir->bimbinganTa,
                'revisiList'    => $dataTugasAkhir->revisiTa,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menyetujui sebuah sesi bimbingan.
     */
    public function setujui(BimbinganTA $bimbingan) // Route Model Binding
    {
        try {
            $this->bimbinganService->updateBimbinganStatus($bimbingan, 'disetujui');
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Bimbingan berhasil diterima.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menolak sebuah sesi bimbingan dengan catatan.
     */
    public function tolak(Request $request, BimbinganTA $bimbingan) // Route Model Binding
    {
        $validated = $request->validate(['komentar_penolakan' => 'required|string|max:1000']);
        try {
            $this->bimbinganService->updateBimbinganStatus($bimbingan, 'ditolak', $validated['komentar_penolakan']);
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Ditolak!',
                'message' => 'Bimbingan berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menyetujui perubahan jadwal.
     */
    public function terimaJadwal(HistoryPerubahanJadwal $perubahan) // Route Model Binding
    {
        try {
            $this->bimbinganService->approveScheduleChange($perubahan);
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Disetujui!',
                'message' => 'Perubahan jadwal disetujui.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menyetujui pembatalan Tugas Akhir.
     */
    public function terimaPembatalanTugasAkhir(TugasAkhir $tugasAkhir) // Route Model Binding
    {
        try {
            $statusAkhir = $this->bimbinganService->approveThesisCancellation($tugasAkhir);
            $message = $statusAkhir === 'dibatalkan'
                ? 'TA telah final dibatalkan karena semua pembimbing setuju.'
                : 'Persetujuan Anda telah disimpan. Menunggu verifikasi pembimbing lain.';

            return back()->with('alert', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $message]);
        } catch (\Exception $e) {
            return back()->with('alert', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Menolak pembatalan Tugas Akhir.
     */
    public function tolakPembatalanTugasAkhir(Request $request, TugasAkhir $tugasAkhir)
    {
        $validated = $request->validate(['catatan_penolakan' => 'required|string|max:1000']);
        try {
            $this->bimbinganService->rejectThesisCancellation($tugasAkhir, $validated['catatan_penolakan']);
            return back()->with('alert', [
                'type' => 'success',
                'title' => 'Pembatalan Ditolak',
                'message' => 'Status TA telah dikembalikan.'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
}
