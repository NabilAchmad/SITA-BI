<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;

class CatatanBimbinganController extends Controller
{
    protected BimbinganService $bimbinganService;

    public function __construct(BimbinganService $bimbinganService)
    {
        $this->bimbinganService = $bimbinganService;
    }

    /**
     * Menyimpan catatan baru (feedback/diskusi) dari dosen.
     */
    public function store(Request $request, TugasAkhir $tugasAkhir)
    {
        $data = $request->validate([
            'catatan' => 'required|string|max:5000',
        ]);

        try {
            $this->bimbinganService->createCatatan($tugasAkhir, $data);

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Catatan telah ditambahkan ke log bimbingan.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Gagal menyimpan catatan: ' . $e->getMessage()
            ]);
        }
    }
}
