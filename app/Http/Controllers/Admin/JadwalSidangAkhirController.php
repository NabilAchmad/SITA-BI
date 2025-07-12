<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignPengujiRequest;
use App\Http\Requests\Admin\StoreJadwalRequest;
use App\Http\Requests\Admin\TandaiSidangRequest;
use App\Http\Requests\Admin\UpdateJadwalRequest;
use App\Models\JadwalSidang;
use App\Models\Sidang;
use App\Services\Admin\JadwalSidangService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JadwalSidangAkhirController extends Controller
{
    protected JadwalSidangService $jadwalSidangService;

    public function __construct(JadwalSidangService $jadwalSidangService)
    {
        $this->jadwalSidangService = $jadwalSidangService;
    }

    /**
     * ✅ PERBAIKAN: Menerapkan middleware untuk seluruh controller.
     * Ini adalah cara modern di Laravel 11 untuk melindungi semua metode
     * di dalam controller ini. Hanya pengguna dengan izin 'manage sidang'
     * yang bisa mengaksesnya.
     */
    public static function middleware(): array
    {
        return [
            'permission:manage sidang',
        ];
    }

    /**
     * Menampilkan data hitungan untuk dashboard.
     */
    public function dashboard()
    {
        $dashboardCounts = $this->jadwalSidangService->getDashboardCounts();
        return view('admin.sidang.dashboard.dashboard', $dashboardCounts);
    }

    /**
     * Menampilkan halaman utama untuk manajemen sidang akhir.
     */
    public function SidangAkhir(Request $request)
    {
        $data = $this->jadwalSidangService->getSidangAkhirLists($request);
        return view('admin.sidang.akhir.views.mhs-sidang', $data);
    }

    /**
     * Menyimpan jadwal sidang baru.
     */
    public function store(StoreJadwalRequest $request): JsonResponse
    {
        try {
            $this->jadwalSidangService->createJadwal($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang akhir berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Memperbarui jadwal sidang yang ada.
     */
    public function update(UpdateJadwalRequest $request, JadwalSidang $jadwal): JsonResponse
    {
        try {
            $result = $this->jadwalSidangService->updateJadwal($jadwal, $request->validated());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menyimpan dosen penguji untuk sebuah sidang.
     */
    public function simpanPenguji(AssignPengujiRequest $request, Sidang $sidang): JsonResponse
    {
        try {
            $this->jadwalSidangService->assignPenguji($sidang, $request->validated());
            return response()->json(['success' => true, 'message' => 'Dosen penguji berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan penguji: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan detail jadwal sidang.
     */
    public function show(int $sidang_id)
    {
        $viewData = $this->jadwalSidangService->getJadwalDetailsForView($sidang_id);
        return view('admin.sidang.akhir.modal.detail-jadwal', $viewData);
    }

    /**
     * Menandai status akhir dari sebuah sidang (lulus/mengulang).
     */
    public function tandaiSidang(TandaiSidangRequest $request, int $sidang_id): JsonResponse
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
            return response()->json(['success' => false, 'message' => 'Data sidang tidak ditemukan.'], 404);
        } catch (HttpException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            report($e);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}
