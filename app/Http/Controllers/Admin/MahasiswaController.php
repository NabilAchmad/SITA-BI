<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa; // ✅ PASTIKAN MODEL DI-IMPORT
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Services\Admin\MahasiswaService;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Menerapkan middleware untuk seluruh controller.
     * Hanya pengguna dengan izin 'manage user accounts' yang bisa mengakses
     * semua method di dalam controller ini.
     */
    public static function middleware(): array
    {
        return [
            'permission:manage user accounts',
        ];
    }

    /**
     * ✅ PERBAIKAN: Gunakan constructor property promotion.
     * Ini cara ringkas untuk mendeklarasikan dan menginisialisasi properti.
     * Method __construct yang lama bisa dihapus.
     */
    public function __construct(protected MahasiswaService $mahasiswaService) {}

    /**
     * Tampilkan daftar mahasiswa.
     * (Tidak ada perubahan di sini, sudah bagus)
     */
    public function index(Request $request)
    {
        $mahasiswa = $this->mahasiswaService->getMahasiswaWithFilters($request);

        if ($request->ajax()) {
            return view('admin.kelola-akun.mahasiswa.crud-mahasiswa.read', compact('mahasiswa'))->render();
        }

        return view('admin.kelola-akun.mahasiswa.views.kelolaMahasiswa', compact('mahasiswa'));
    }

    /**
     * ✅ PERBAIKAN: Menggunakan Route Model Binding.
     * Laravel akan secara otomatis mencari Mahasiswa berdasarkan parameter dari route.
     * Jika tidak ditemukan, otomatis akan menampilkan error 404.
     */
    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $this->mahasiswaService->updateMahasiswa($request->validated(), $mahasiswa);

        return redirect()
            ->route('akun-mahasiswa.kelola')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function search(Request $request)
    {
        $query = Mahasiswa::with('user');

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $mahasiswa = $query->paginate(10);

        return view('admin.kelola-akun.mahasiswa.views.kelolaMahasiswa', compact('mahasiswa'));
    }
}
