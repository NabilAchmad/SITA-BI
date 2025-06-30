<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\StoreTawaranTopikRequest;
use App\Http\Requests\Dosen\UpdateTawaranTopikRequest;
use App\Models\TawaranTopik;
use App\Services\Dosen\TawaranTopikService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // PERBAIKAN: Tambahkan use statement untuk Auth Facade

class TawaranTopikController extends Controller
{
    protected TawaranTopikService $tawaranTopikService;

    public function __construct(TawaranTopikService $tawaranTopikService)
    {
        $this->tawaranTopikService = $tawaranTopikService;
    }

    public function read()
    {
        $tawaranTopiks = $this->tawaranTopikService->getActiveTopics();
        $tawaranTopik = new TawaranTopik();
        return view('dosen.tawaran-topik.views.readTawaranTopik', compact('tawaranTopiks', 'tawaranTopik'));
    }

    public function store(StoreTawaranTopikRequest $request)
    {
        $this->tawaranTopikService->createTopic($request->validated());
        return redirect()->route('dosen.tawaran-topik.index')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil ditambahkan.'
        ]);
    }

    public function update(UpdateTawaranTopikRequest $request, TawaranTopik $tawaranTopik)
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
        // PERBAIKAN: Menggunakan sintaks Auth::id() yang lebih eksplisit dan ramah untuk IDE.
        if ($tawaranTopik->user_id !== Auth::id()) {
            return redirect()->route('dosen.tawaran-topik.index')->with('alert', [
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

    // ... method trashed, restore, forceDelete ...
    public function trashed()
    {
        $tawaranTopiks = $this->tawaranTopikService->getTrashedTopics();
        return view('dosen.tawaran-topik.views.trashedTawaranTopik', compact('tawaranTopiks'));
    }

    public function restore($id)
    {
        $this->tawaranTopikService->restoreTopic($id);
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil dipulihkan.'
        ]);
    }

    public function forceDelete($id)
    {
        $this->tawaranTopikService->forceDeleteTopic($id);
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Tawaran topik berhasil dihapus permanen.'
        ]);
    }

    public function forceDeleteAll()
    {
        $this->tawaranTopikService->forceDeleteAllTopics();
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua tawaran topik di trash telah dihapus permanen.'
        ]);
    }
}
