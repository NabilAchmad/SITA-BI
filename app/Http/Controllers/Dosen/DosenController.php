<?php

namespace App\Http\Controllers\Dosen;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

        $dosenList = $query->paginate(10); // Ganti 10 sesuai jumlah per halaman

        return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList'));
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

    // Form tambah dosen
    public function create()
    {
        return view('admin.kelola-akun.dosen.views.createDosen');
    }

    // Proses simpan dosen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nidn' => 'required|string|unique:dosen,nidn',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($request) {
            // Simpan ke tabel users
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Hubungkan dengan role 'dosen'
            $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();
            $user->roles()->attach($roleDosen->id); // via relasi belongsToMany

            // Simpan ke tabel dosen
            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
            ]);
        });

        return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);
        return view('admin.kelola-akun.dosen.views.editAkun', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dosen->user->id,
            'nidn' => 'required|string|unique:dosen,nidn,' . $dosen->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $dosen->user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $dosen->user->password,
        ]);

        $dosen->update([
            'nidn' => $request->nidn,
        ]);

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
