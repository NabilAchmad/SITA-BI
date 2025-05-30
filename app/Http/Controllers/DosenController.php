<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    // Menampilkan daftar semua akun dosen
    public function index(Request $request)
    {
        $query = Dosen::with(['user.roles']);

        // Filter berdasarkan nama dosen (yang disimpan di relasi user.name)
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $roles = Role::whereIn('nama_role', ['kaprodi', 'kajur'])->get();

        $dosenList = $query->paginate(10); // Ganti 10 sesuai jumlah per halaman

        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
    }

    public function getTotalAktif()
    {
        // Asumsi: Semua dosen yang ada di tabel 'dosen' dianggap aktif
        // Jika ada kolom 'status' di tabel dosen, bisa filter: where('status', 'aktif')
        $totalDosen = Dosen::count();

        return response()->json([
            'total' => $totalDosen
        ]);
    }

    // Proses simpan dosen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nidn' => 'required|string|unique:dosen,nidn',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id' => 'nullable|in:2,3', // hanya kaprodi / kajur jika dipilih
        ]);

        DB::transaction(function () use ($request) {
            // Buat user
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Ambil role dosen wajib
            $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();
            $roleIds = [$roleDosen->id];

            // Tambahkan role kaprodi/kajur jika dipilih
            if ($request->filled('role_id')) {
                $roleIds[] = $request->role_id;
            }

            // Simpan ke tabel user_roles
            $user->roles()->attach($roleIds);

            // Simpan data dosen
            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
            ]);
        });

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::with('user.roles')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dosen->user->id,
            'nidn' => 'required|string|unique:dosen,nidn,' . $dosen->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_id' => 'nullable|in:2,3',
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $user = $dosen->user;

            // Update user
            $user->update([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);

            // Update NIDN dosen
            $dosen->update([
                'nidn' => $request->nidn,
            ]);

            // Ambil role dosen wajib
            $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();
            $roleIds = [$roleDosen->id];

            // Tambahkan role tambahan jika dipilih (kajur/kaprodi)
            if ($request->filled('role_id')) {
                $roleIds[] = $request->role_id;
            }

            // Sync roles (hapus sebelumnya, ganti dengan yang baru)
            $user->roles()->sync($roleIds);
        });

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);

        // Hapus user & dosen
        $dosen->user->delete();
        $dosen->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Akun dosen berhasil dihapus.']);
        }

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil dihapus.');
    }
}
