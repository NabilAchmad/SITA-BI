<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DosenController extends Controller
{
    /**
     * Tampilkan daftar dosen dengan opsi pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $query = Dosen::with('user.roles');

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            );
        }

        $query->orderByDesc('updated_at')->orderByDesc('created_at');

        $dosenList = $query->paginate(10);
        $roles = Role::whereIn('nama_role', ['kaprodi', 'kajur'])->get();

        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
    }

    /**
     * Ambil total dosen aktif.
     */
    public function getTotalAktif()
    {
        $totalDosen = Dosen::count();

        return response()->json(['total' => $totalDosen]);
    }

    /**
     * Simpan data dosen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'nidn'     => 'required|string|unique:dosen,nidn',
            'password' => ['required', Password::min(8)],
            'role_id'  => 'nullable|in:2,3', // ID kaprodi atau kajur
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['nama'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $roleIds = $this->buildRoleIds($validated['role_id'] ?? null);
            $user->roles()->attach($roleIds);

            Dosen::create([
                'user_id' => $user->id,
                'nidn'    => $validated['nidn'],
            ]);
        });

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    /**
     * Perbarui data dosen.
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::with('user.roles')->findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $dosen->user->id,
            'nidn'     => 'required|string|unique:dosen,nidn,' . $dosen->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_id'  => 'nullable|in:2,3',
        ]);

        DB::transaction(function () use ($validated, $dosen, $request) {
            $user = $dosen->user;

            $user->update([
                'name'     => $validated['nama'],
                'email'    => $validated['email'],
                'password' => $request->filled('password')
                    ? Hash::make($validated['password'])
                    : $user->password,
            ]);

            $dosen->update(['nidn' => $validated['nidn']]);

            $roleIds = $this->buildRoleIds($validated['role_id'] ?? null);
            $user->roles()->sync($roleIds);
        });

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil diperbarui.');
    }

    /**
     * Hapus akun dosen dan user terkait.
     */
    public function destroy($id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);

        DB::transaction(function () use ($dosen) {
            $dosen->user->delete();
            $dosen->delete();
        });

        return request()->ajax()
            ? response()->json(['message' => 'Akun dosen berhasil dihapus.'])
            : redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil dihapus.');
    }

    /**
     * Dashboard untuk dosen atau kaprodi.
     */
    public function dashboard()
    {
        return view('kaprodi.dashboard'); // Ubah sesuai kebutuhan
    }

    /**
     * Buat array ID role untuk disimpan atau disinkronisasi.
     */
    private function buildRoleIds($extraRoleId = null)
    {
        $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();
        $roleIds = [$roleDosen->id];

        if ($extraRoleId && in_array($extraRoleId, [2, 3])) {
            $roleIds[] = (int) $extraRoleId;
        }

        return $roleIds;
    }
}
