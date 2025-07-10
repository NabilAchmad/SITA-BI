<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StoreTawaranTopikRequest;
use App\Http\Requests\Dosen\UpdateTawaranTopikRequest;
use App\Models\HistoryTopikMahasiswa;
use App\Models\Mahasiswa;
use App\Models\TawaranTopik;
use App\Services\Dosen\TawaranTopikService;
use App\Services\TopikPengajuanService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TawaranTopikController extends Controller
{
    protected TawaranTopikService $tawaranTopikService;
    protected TopikPengajuanService $topikPengajuanService;

    public function __construct(TawaranTopikService $tawaranTopikService, TopikPengajuanService $topikPengajuanService)
    {
        $this->tawaranTopikService = $tawaranTopikService;
        $this->topikPengajuanService = $topikPengajuanService;
    }

    /**
     * ✅ PERBAIKAN: Nama metode diubah dari 'read' menjadi 'index'.
     * Ini agar sesuai dengan konvensi Route::resource Laravel.
     */
    public function index(Request $request)
    {
        // Data untuk Tab 1: Daftar topik yang ditawarkan
        $tawaranTopiks = $this->tawaranTopikService->getActiveTopics();
        $tawaranTopik = new TawaranTopik(); // Untuk modal 'create'

        // Data untuk Tab 2: Daftar mahasiswa yang mengajukan topik
        $applications = $this->topikPengajuanService->getApplicationsForDosen(Auth::user()->dosen, $request);

        // Data untuk dropdown filter prodi
        $prodiList = Mahasiswa::select('prodi')->distinct()->pluck('prodi');

        return view('dosen.tawaran-topik.views.readTawaranTopik', compact(
            'tawaranTopiks',
            'tawaranTopik',
            'applications',
            'prodiList'
        ));
    }

    /**
     * Menyimpan tawaran topik baru.
     */
    public function store(StoreTawaranTopikRequest $request): RedirectResponse
    {
        $this->tawaranTopikService->createTopic($request->validated());
        return redirect()->route('dosen.tawaran-topik.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil ditambahkan.'
        ]);
    }

    /**
     * Memperbarui tawaran topik yang ada.
     */
    public function update(UpdateTawaranTopikRequest $request, TawaranTopik $tawaranTopik): RedirectResponse
    {
        $this->tawaranTopikService->updateTopic($tawaranTopik, $request->validated());
        return redirect()->route('dosen.tawaran-topik.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil diperbarui.'
        ]);
    }

    /**
     * Menghapus (soft delete) tawaran topik.
     */
    public function destroy(TawaranTopik $tawaranTopik): RedirectResponse
    {
        // Otorisasi sebaiknya dilakukan di dalam FormRequest atau Policy
        if ($tawaranTopik->user_id !== Auth::id()) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => 'Anda tidak memiliki izin untuk menghapus topik ini.'
            ]);
        }

        $this->tawaranTopikService->deleteTopic($tawaranTopik);

        return redirect()->route('dosen.tawaran-topik.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil dihapus.'
        ]);
    }

    // ... (Sisa method seperti trashed, restore, dll. tidak perlu diubah)

    public function trashed()
    {
        $tawaranTopiks = $this->tawaranTopikService->getTrashedTopics();
        return view('dosen.tawaran-topik.crud-TawaranTopik.trashed', ['tawaranTopiks' => $tawaranTopiks]);
    }

    public function restore($id): RedirectResponse
    {
        $this->tawaranTopikService->restoreTopic($id);
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil dipulihkan.'
        ]);
    }

    public function forceDelete($id): RedirectResponse
    {
        $this->tawaranTopikService->forceDeleteTopic($id);
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil dihapus permanen.'
        ]);
    }

    public function forceDeleteAll(): RedirectResponse
    {
        $this->tawaranTopikService->forceDeleteAllTopics();
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua tawaran topik yang dihapus berhasil dihapus permanen.'
        ]);
    }

    public function approveApplication(HistoryTopikMahasiswa $application): RedirectResponse
    {
        try {
            $this->topikPengajuanService->approveApplication($application);
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Pengajuan mahasiswa telah disetujui.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function rejectApplication(HistoryTopikMahasiswa $application): RedirectResponse
    {
        $this->topikPengajuanService->rejectApplication($application);
        return redirect()->back()->with('alert', [
            'type' => 'info',
            'title' => 'Info',
            'message' => 'Pengajuan mahasiswa telah ditolak.'
        ]);
    }
}
