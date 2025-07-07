<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Services\Admin\DosenService;
use App\Models\Dosen;
use Spatie\Permission\Models\Role; // ✅ PERBAIKAN: Gunakan model Role dari Spatie
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DosenController extends Controller
{
    protected DosenService $dosenService;

    public function __construct(DosenService $dosenService)
    {
        $this->dosenService = $dosenService;

    }

    /**
     * Menampilkan halaman utama untuk mengelola akun dosen.
     */
    public function index(Request $request): View
    {
        $dosenList = $this->dosenService->getDosenWithFilters($request);

        // Mengambil role dari model Spatie dengan kolom 'name'
        $roles = Role::whereIn('name', ['kajur', 'kaprodi-d3', 'kaprodi-d4'])->get();

        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
    }

    /**
     * Menyimpan data dosen baru.
     */
    public function store(StoreDosenRequest $request): RedirectResponse
    {
        $this->dosenService->createDosen($request->validated());
        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data dosen.
     * (Catatan: Ini mungkin lebih baik ditampilkan dalam modal, tapi logikanya tetap sama)
     */
    public function edit(Dosen $dosen): View
    {
        // Mengambil role dari model Spatie dengan kolom 'name'
        $roles = Role::whereIn('name', ['kajur', 'kaprodi-d3', 'kaprodi-d4'])->get();
        $dosen->load('user.roles'); // Memuat relasi untuk data yang sudah ada

        return view('admin.kelola-akun.dosen.modal.edit', compact('dosen', 'roles'));
    }

    /**
     * Memperbarui data dosen.
     */
    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $this->dosenService->updateDosen($dosen, $request->validated());
        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil diperbarui.');
    }

    /**
     * Menghapus data dosen.
     */
    public function destroy(Dosen $dosen): RedirectResponse|JsonResponse
    {
        $this->dosenService->deleteDosen($dosen);

        if (request()->ajax()) {
            return response()->json(['message' => 'Akun dosen berhasil dihapus.']);
        }

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil dihapus.');
    }
}
