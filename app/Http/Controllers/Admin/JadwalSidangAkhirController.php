<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Admin\JadwalSidangService; // <-- Import service baru
use App\Http\Requests\Admin\StoreJadwalRequest; // <-- Import FormRequest yang sudah kita buat
use App\Http\Requests\Admin\UpdateJadwalRequest; // <-- Import FormRequest baru
use App\Http\Requests\Admin\AssignPengujiRequest; // <-- Import FormRequest baru
use App\Http\Requests\Admin\TandaiSidangRequest; // <-- Import FormRequest baru
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JadwalSidangAkhirController extends Controller
{
    protected JadwalSidangService $jadwalSidangService;
    // Menggunakan dependency injection untuk memasukkan service
    public function __construct(JadwalSidangService $jadwalSidangService)
    {
        $this->jadwalSidangService = $jadwalSidangService;
    }

    /**
     * Menampilkan data hitungan untuk dashboard.
     * Logika query sudah dipindahkan ke service.
     */
    public function dashboard()
    {
        // Panggil satu method dari service untuk mendapatkan semua data
        $dashboardCounts = $this->jadwalSidangService->getDashboardCounts();

        // Kirim data ke view menggunakan nama variabel yang sama
        return view('admin.sidang.dashboard.dashboard', $dashboardCounts);
    }

    /**
     * =========================================================================
     * PERBAIKAN: Method ini sekarang hanya memanggil service dan menampilkan view.
     * Semua logika query, filter, dan paginasi sudah ditangani oleh service.
     * =========================================================================
     */
    public function SidangAkhir(Request $request)
    {
        // Panggil satu method dari service untuk mendapatkan semua data yang dibutuhkan
        $data = $this->jadwalSidangService->getSidangAkhirLists($request);

        // Kirim data yang sudah di-compact dari service langsung ke view
        return view('admin.sidang.akhir.views.mhs-sidang', $data);
    }

    /**
     * =========================================================================
     * FUNGSI STORE YANG TELAH DIREFACTOR
     * =========================================================================
     * Method ini sekarang menggunakan FormRequest untuk validasi dan memanggil
     * Service untuk menjalankan semua logika bisnis.
     */
    public function store(StoreJadwalRequest $request)
    {
        try {
            // 1. Validasi request secara otomatis oleh StoreJadwalRequest.
            //    Jika gagal, Laravel akan otomatis mengembalikan error validasi.

            // 2. Jika validasi berhasil, panggil method 'createJadwal' dari service
            //    dengan data yang sudah bersih dan tervalidasi.
            $this->jadwalSidangService->createJadwal($request->validated());

            // 3. Kembalikan response sukses dalam format JSON.
            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang akhir berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            // 4. Jika terjadi error di dalam service (misal: jadwal bentrok),
            //    tangkap exception dan kembalikan response gagal dengan pesan error.
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422); // Kode status 422 (Unprocessable Entity) cocok untuk error validasi bisnis.
        }
    }

    /**
     * =========================================================================
     * FUNGSI UPDATE YANG TELAH DIREFACTOR
     * =========================================================================
     * Method ini sekarang menggunakan FormRequest untuk validasi dan memanggil
     * Service untuk menjalankan semua logika bisnis.
     */
    public function update(UpdateJadwalRequest $request, JadwalSidang $jadwal)
    {
        try {
            // 1. Validasi otomatis oleh UpdateJadwalRequest.

            // 2. Panggil service untuk menjalankan semua logika update.
            //    Service akan mengembalikan array dengan format yang sama seperti kode asli.
            $result = $this->jadwalSidangService->updateJadwal($jadwal, $request->validated());

            // 3. Kembalikan response JSON yang sudah diformat oleh service.
            return response()->json($result);
        } catch (\Exception $e) {
            // 4. Tangkap jika ada error dari service dan kembalikan response error.
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * =========================================================================
     * FUNGSI SIMPAN PENGUJI YANG TELAH DIREFACTOR
     * =========================================================================
     * Method ini sekarang menggunakan FormRequest untuk validasi dan memanggil
     * Service untuk menjalankan semua logika bisnis.
     */
    public function simpanPenguji(AssignPengujiRequest $request, Sidang $sidang)
    {
        try {
            // 1. Validasi otomatis oleh AssignPengujiRequest.

            // 2. Panggil service untuk menjalankan logika penyimpanan penguji.
            $this->jadwalSidangService->assignPenguji($sidang, $request->validated());

            // 3. Kembalikan response sukses.
            return response()->json(['success' => true, 'message' => 'Dosen penguji berhasil disimpan.']);
        } catch (\Exception $e) {
            // 4. Tangkap jika ada error dari service dan kembalikan response gagal.
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan penguji: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan detail jadwal sidang.
     * Logika bisnis sudah dipindahkan ke service.
     *
     * @param int $sidang_id
     * @return \Illuminate\View\View
     */
    public function show($sidang_id)
    {
        // Panggil service untuk mendapatkan semua data yang dibutuhkan view
        $viewData = $this->jadwalSidangService->getJadwalDetailsForView($sidang_id);

        // Kirim data ke view. 
        // Laravel secara otomatis akan mengekstrak keys dari array menjadi variabel.
        return view('admin.sidang.akhir.modal.detail-jadwal', $viewData);
    }

    /**
     * Menandai status akhir sidang.
     */
    public function tandaiSidang(TandaiSidangRequest $request, $sidang_id): JsonResponse
    {
        try {
            $this->jadwalSidangService->tandaiStatusAkhir(
                $sidang_id,
                $request->validated('status')
            );

            return response()->json([
                'success' => true,
                'message' => 'Status sidang berhasil diperbarui.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data sidang tidak ditemukan.',
            ], 404);
        } catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            // Menangkap error tak terduga lainnya
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }
}
