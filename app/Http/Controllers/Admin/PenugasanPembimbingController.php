<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePembimbingRequest;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Services\Admin\PenugasanService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ✅ PENTING: Import trait otorisasi

class PenugasanPembimbingController extends Controller
{
    use AuthorizesRequests; // ✅ PENTING: Gunakan trait agar bisa memanggil $this->authorize()

    /**
     * ✅ PERBAIKAN: Gunakan constructor property promotion untuk service.
     * Ini cara ringkas untuk mendeklarasikan dan menginisialisasi properti.
     */
    public function __construct(protected PenugasanService $penugasanService)
    {
        /**
         * ✅ PERBAIKAN HAK AKSES: Middleware telah dihapus dari sini.
         * Sesuai praktik Laravel terbaru, middleware sekarang harus
         * diterapkan langsung di file route (misalnya, routes/web.php).
         */
    }

    /**
     * Menampilkan daftar mahasiswa yang sudah memiliki pembimbing.
     */
    public function indexPembimbing(Request $request)
    {
        $tugasAkhirList = $this->penugasanService->getMahasiswaWithPembimbing($request);

        // Panggil method helper untuk menghindari duplikasi kode.
        $dosenList = $this->_getDosenList();

        return view('admin.mahasiswa.views.list-mhs', compact('tugasAkhirList', 'dosenList'));
    }

    /**
     * Menampilkan daftar mahasiswa yang membutuhkan penugasan pembimbing.
     */
    public function indexWithoutPembimbing(Request $request)
    {
        $tugasAkhirList = $this->penugasanService->getMahasiswaNeedingPembimbing($request);

        // Panggil method helper untuk menghindari duplikasi kode.
        $dosenList = $this->_getDosenList();

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
            // Sebaiknya log error ini untuk debugging di masa depan
            // Log::error('Gagal menyimpan pembimbing: ' . $e->getMessage());

            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat menyimpan data.' // Pesan lebih umum untuk user
            ]);
        }
    }

    /**
     * Method update hanya memanggil method store untuk konsistensi.
     */
    public function update(UpdatePembimbingRequest $request, TugasAkhir $tugasAkhir)
    {
        // Otorisasi spesifik untuk aksi UPDATE.
        // $this->authorize('update', $tugasAkhir);

        try {
            // Logika sama dengan store, hanya otorisasi yang berbeda.
            $this->penugasanService->assignOrUpdatePembimbing($tugasAkhir, $request->validated());

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data pembimbing berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ]);
        }
    }

    /**
     * Method private untuk mengambil daftar dosen.
     * Mengurangi duplikasi dan memusatkan logika query.
     */
    private function _getDosenList()
    {
        return Dosen::with('user')
            ->join('users', 'dosen.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('dosen.*') // Penting untuk menghindari kolom ambigu
            ->get();
    }
}
