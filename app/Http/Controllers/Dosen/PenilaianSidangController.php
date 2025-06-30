<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StorePenilaianRequest;
use App\Models\Sidang;
use App\Services\Dosen\PenilaianSidangService;
use Illuminate\Http\Request;

class PenilaianSidangController extends Controller
{
    protected PenilaianSidangService $penilaianSidangService;

    public function __construct(PenilaianSidangService $penilaianSidangService)
    {
        $this->penilaianSidangService = $penilaianSidangService;
    }

    /**
     * Menampilkan daftar sidang yang perlu dinilai.
     */
    public function index()
    {
        $daftarSidang = $this->penilaianSidangService->getSidangUntukDinilai();
        return view('dosen.sidang.penilaian.index', compact('daftarSidang'));
    }

    /**
     * Menampilkan form untuk memberikan nilai pada sidang tertentu.
     */
    public function create(Sidang $sidang)
    {
        // Memuat relasi yang dibutuhkan oleh form
        $sidang->load('tugasAkhir.mahasiswa.user');
        return view('dosen.sidang.penilaian.form', compact('sidang'));
    }

    /**
     * Menyimpan data penilaian.
     */
    public function store(StorePenilaianRequest $request, Sidang $sidang)
    {
        try {
            $this->penilaianSidangService->storeNilai($sidang, $request->validated());

            return redirect()->route('dosen.penilaian-sidang.index')
                ->with('alert', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Nilai berhasil disimpan. Terima kasih.'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
