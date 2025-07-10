<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Services\Admin\DosenService;
use App\Models\Dosen;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DosenController extends Controller
{
    /**
     * ✅ PRAKTIK TERBAIK: Menggunakan constructor property promotion.
     * Service di-inject secara otomatis oleh Laravel, membuat controller ini
     * siap untuk digunakan tanpa perlu inisialisasi manual.
     */
    public function __construct(protected DosenService $dosenService) {}

    /**
     * Menampilkan halaman utama untuk mengelola akun dosen.
     * Controller ini hanya mengambil data yang sudah disiapkan oleh Service.
     */
    public function index(Request $request): View
    {
        $dosenList = $this->dosenService->getDosenWithFilters($request);

        // Mengambil daftar peran jabatan untuk ditampilkan di form tambah/edit.
        $roles = Role::whereIn('name', ['kajur', 'kaprodi-d3', 'kaprodi-d4'])->get();

        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
    }

    /**
     * Menyimpan data dosen baru.
     * Tanggung jawab validasi diserahkan ke StoreDosenRequest.
     * Tanggung jawab logika bisnis diserahkan ke DosenService.
     */
    public function store(StoreDosenRequest $request): RedirectResponse
    {
        $this->dosenService->createDosen($request->validated());

        return redirect()->route('admin.akun.dosen.index')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data dosen.
     * ✅ PRAKTIK TERBAIK: Menggunakan Route Model Binding (Dosen $dosen).
     * Laravel secara otomatis akan menemukan data Dosen berdasarkan ID dari URL.
     */
    public function edit(Dosen $dosen): View
    {
        $roles = Role::whereIn('name', ['kajur', 'kaprodi-d3', 'kaprodi-d4'])->get();

        // Memuat relasi untuk menampilkan peran yang sudah dimiliki dosen saat ini di form.
        $dosen->load('user.roles');

        return view('admin.kelola-akun.dosen.modal.edit', compact('dosen', 'roles'));
    }

    /**
     * Memperbarui data dosen.
     */
    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $this->dosenService->updateDosen($dosen, $request->validated());

        return redirect()->route('admin.akun.dosen.index')->with('success', 'Akun dosen berhasil diperbarui.');
    }

    /**
     * Menghapus data dosen.
     */
    public function destroy(Dosen $dosen): RedirectResponse|JsonResponse
    {
        $this->dosenService->deleteDosen($dosen);

        // Memberikan respons yang sesuai jika request berasal dari AJAX (misalnya, dari skrip SweetAlert).
        if (request()->ajax()) {
            return response()->json(['message' => 'Akun dosen berhasil dihapus.']);
        }

        return redirect()->route('admin.akun.dosen.index')->with('success', 'Akun dosen berhasil dihapus.');
    }
}
