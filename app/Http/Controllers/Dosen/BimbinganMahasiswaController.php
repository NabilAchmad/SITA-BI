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

    public function dashboard(Request $request)
    {
        $mahasiswaList = $this->bimbinganService->getFilteredMahasiswaBimbingan($request);
        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    public function showDetail(int $mahasiswaId)
    {
        try {
            $dataTugasAkhir = $this->bimbinganService->getTugasAkhirDetailForMahasiswa($mahasiswaId);
            return view('dosen.bimbingan.detail-bimbingan.detail', [
                'mahasiswa'     => $dataTugasAkhir->mahasiswa,
                'tugasAkhir'    => $dataTugasAkhir,
                'bimbinganList' => $dataTugasAkhir->bimbinganTa,
                'revisiList'    => $dataTugasAkhir->revisiTa,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', ['type' => 'error', 'title' => 'Tidak Ditemukan', 'message' => 'Data tugas akhir untuk mahasiswa tersebut tidak ditemukan.']);
        } catch (\Exception $e) {
            return redirect()->route('dosen.bimbingan.index')->with('alert', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $e->getMessage()]);
        }
    }

    public function setujui(BimbinganTA $bimbingan)
    {
        try {
            $this->bimbinganService->updateBimbinganStatus($bimbingan, BimbinganTA::STATUS_DISETUJUI);
            return redirect()->back()->with('alert', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Bimbingan berhasil diterima.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', ['type' => 'error', 'title' => 'Gagal!', 'message' => $e->getMessage()]);
        }
    }

    public function tolakBimbingan(Request $request, BimbinganTA $bimbingan)
    {
        $request->validate(['komentar_penolakan' => 'required|string|max:1000']);
        try {
            $this->bimbinganService->updateBimbinganStatus($bimbingan, BimbinganTA::STATUS_DITOLAK, $request->komentar_penolakan);
            return redirect()->back()->with('alert', ['type' => 'warning', 'title' => 'Bimbingan Ditolak!', 'message' => 'Penolakan bimbingan berhasil dikirim.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', ['type' => 'error', 'title' => 'Gagal!', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Menandai sebuah sesi bimbingan sebagai 'selesai'.
     *
     * @param BimbinganTA $bimbingan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selesaiBimbingan(BimbinganTA $bimbingan)
    {
        try {
            // Panggil fungsi 'selesaikanBimbingan' dari service Anda
            $this->bimbinganService->selesaikanBimbingan($bimbingan);

            // Jika berhasil, kembalikan dengan pesan sukses
            return redirect()->back()->with('alert', [
                'type'    => 'success',
                'title'   => 'Selesai!',
                'message' => 'Bimbingan telah ditandai selesai dan sesi telah dicatat.'
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error (termasuk error otorisasi dari service),
            // tangkap dan tampilkan pesannya
            return redirect()->back()->with('alert', [
                'type'    => 'error',
                'title'   => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function terimaPerubahanJadwal(HistoryPerubahanJadwal $perubahan)
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

    public function tolakPerubahanJadwal(Request $request, HistoryPerubahanJadwal $perubahan)
    {
        $validated = $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        try {
            $this->bimbinganService->rejectScheduleChange($perubahan, $validated['komentar']);
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Ditolak!',
                'message' => 'Perubahan jadwal berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function terimaPembatalanTugasAkhir(TugasAkhir $tugasAkhir)
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

    public function tolakPembatalanTugasAkhir(Request $request, TugasAkhir $tugasAkhir)
    {
        $validated = $request->validate(['catatan_penolakan' => 'required|string|max:1000']);
        try {
            $this->bimbinganService->rejectThesisCancellation($tugasAkhir, $validated['catatan_penolakan']);
            return back()->with('alert', ['type' => 'success', 'title' => 'Pembatalan Ditolak', 'message' => 'Status TA telah dikembalikan.']);
        } catch (\Exception $e) {
            return back()->with('alert', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }
    }
}
