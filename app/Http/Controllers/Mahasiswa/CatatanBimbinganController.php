<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Mahasiswa\TugasAkhirService;
use Illuminate\Http\Request;

class CatatanBimbinganController extends Controller
{
    protected TugasAkhirService $tugasAkhirService;

    public function __construct(TugasAkhirService $tugasAkhirService)
    {
        $this->tugasAkhirService = $tugasAkhirService;
    }

    /**
     * Menyimpan catatan baru dari mahasiswa ke log bimbingan.
     */
    public function store(Request $request, TugasAkhir $tugasAkhir)
    {
        $data = $request->validate([
            'catatan'         => 'required|string|max:2000',
            'bimbingan_ta_id' => 'required|exists:bimbingan_ta,id', // ✅ Tambahkan ini
        ]);

        try {
            $this->tugasAkhirService->createCatatanForMahasiswa($tugasAkhir, $data);

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Catatan Anda telah terkirim ke dosen pembimbing.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Gagal mengirim catatan: ' . $e->getMessage()
            ]);
        }
    }
}
