<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TawaranTopik;
use App\Services\TopikPengajuanService;
use Illuminate\Support\Facades\Auth;

class TopikController extends Controller
{
    protected TopikPengajuanService $topikPengajuanService;

    public function __construct(TopikPengajuanService $topikPengajuanService)
    {
        $this->topikPengajuanService = $topikPengajuanService;
    }

    /**
     * Menampilkan daftar topik yang tersedia untuk mahasiswa.
     */
    public function index()
    {
        $daftarTopik = $this->topikPengajuanService->getAvailableTopics();
        return view('mahasiswa.tugas-akhir.crud-ta.listTopik', compact('daftarTopik'));
    }

    /**
     * Menangani aksi mahasiswa saat mengambil topik.
     */
    public function apply(TawaranTopik $topik)
    {
        try {
            $mahasiswa = Auth::user()->mahasiswa;
            $this->topikPengajuanService->applyForTopic($topik, $mahasiswa);

            return redirect()->route('tugas-akhir.dashboard')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Pengajuan topik telah berhasil dikirim. Mohon tunggu persetujuan dari dosen.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}
