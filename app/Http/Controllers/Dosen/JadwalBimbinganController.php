<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;
use App\Models\BimbinganTA;

class JadwalBimbinganController extends Controller
{
    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    /**
     * Menyimpan jadwal bimbingan baru yang dibuat oleh dosen.
     */
    public function store(Request $request, TugasAkhir $tugasAkhir)
    {
        $data = $request->validate([
            'tanggal_bimbingan' => 'required|date|after_or_equal:today',
            'jam_bimbingan' => 'required|date_format:H:i',
        ]);

        try {
            $this->bimbinganService->createJadwal($tugasAkhir, $data);

            return redirect()->route('dosen.bimbingan.show', $tugasAkhir->id)->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Jadwal bimbingan baru telah berhasil dibuat.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * [BARU] Menangani aksi pembatalan sesi bimbingan oleh dosen.
     */
    public function cancel(BimbinganTA $bimbingan)
    {
        try {
            $this->bimbinganService->cancelBimbingan($bimbingan);
            return redirect()->back()->with('alert', [
                'type' => 'warning',
                'title' => 'Dibatalkan!',
                'message' => 'Sesi bimbingan telah berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    // app/Http/Controllers/Dosen/JadwalBimbinganController.php
    public function selesaikan(BimbinganTA $bimbingan)
    {
        // Otorisasi sederhana bisa ditambahkan di service
        $this->bimbinganService->selesaikanSesi($bimbingan);
        return redirect()->back()->with('alert', ['type' => 'success', 'message' => 'Sesi bimbingan telah ditandai selesai.']);
    }
}
