<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePembimbingRequest; // Menggunakan satu FormRequest
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Services\Admin\PenugasanService;
use Illuminate\Http\Request;

class PenugasanPembimbingController extends Controller
{
    protected PenugasanService $penugasanService;

    public function __construct(PenugasanService $penugasanService)
    {
        $this->penugasanService = $penugasanService;
    }

    /**
     * Menampilkan daftar mahasiswa yang sudah memiliki pembimbing.
     */
    public function indexPembimbing(Request $request)
    {
        $tugasAkhirList = $this->penugasanService->getMahasiswaWithPembimbing($request);
        $dosenList = Dosen::with('user')
            ->join('users', 'dosen.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('dosen.*') // Penting untuk menghindari kolom ambigu
            ->get();

        return view('admin.mahasiswa.views.list-mhs', compact('tugasAkhirList', 'dosenList'));
    }

    /**
     * Menampilkan daftar mahasiswa yang membutuhkan penugasan pembimbing.
     */
    public function indexWithoutPembimbing(Request $request)
    {
        $tugasAkhirList = $this->penugasanService->getMahasiswaNeedingPembimbing($request);
        $dosenList = Dosen::with('user')
            ->join('users', 'dosen.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('dosen.*') // Penting untuk menghindari kolom ambigu
            ->get();

        return view('admin.mahasiswa.views.assign-dospem', compact('tugasAkhirList', 'dosenList'));
    }

    /**
     * Menetapkan atau memperbarui pembimbing untuk Tugas Akhir.
     * Method ini menangani semua skenario (menetapkan baru atau mengedit).
     */
    public function store(UpdatePembimbingRequest $request, TugasAkhir $tugasAkhir)
    {
        try {
            $this->penugasanService->assignOrUpdatePembimbing($tugasAkhir, $request->validated());

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data pembimbing berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Method update hanya memanggil method store untuk konsistensi.
     */
    public function update(UpdatePembimbingRequest $request, TugasAkhir $tugasAkhir)
    {
        return $this->store($request, $tugasAkhir);
    }
}
