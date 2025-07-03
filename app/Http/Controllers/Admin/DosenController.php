<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDosenRequest;
use App\Http\Requests\Admin\UpdateDosenRequest;
use App\Services\Admin\DosenService;
use App\Models\Dosen;
use App\Models\Role;
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

    public function index(Request $request): View
    {
        $dosenList = $this->dosenService->getDosenWithFilters($request);
        // Mengambil role spesifik untuk dropdown di form
        $roles = Role::whereIn('nama_role', ['kaprodi-d3', 'kaprodi-d4', 'kajur'])->get();
        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
    }

    public function store(StoreDosenRequest $request): RedirectResponse
    {
        $this->dosenService->createDosen($request->validated());
        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View
    {
        $roles = Role::whereIn('nama_role', ['kaprodi-d3', 'kaprodi-d4', 'kajur'])->get();
        $dosen->load('user.roles');
        return view('admin.kelola-akun.dosen.modal.edit', compact('dosen', 'roles'));
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $this->dosenService->updateDosen($dosen, $request->validated());
        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse|JsonResponse
    {
        $this->dosenService->deleteDosen($dosen);

        if (request()->ajax()) {
            return response()->json(['message' => 'Akun dosen berhasil dihapus.']);
        }

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil dihapus.');
    }
}
