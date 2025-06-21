<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

// <?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use App\Models\Dosen;
// use App\Models\Role;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\DB;

// class DosenController extends Controller
// {
//     public function index(Request $request)
//     {
//         $query = Dosen::with(['user.roles']);

//         // Filter berdasarkan nama dosen
//         if ($request->has('search')) {
//             $search = $request->input('search');
//             $query->whereHas('user', function ($q) use ($search) {
//                 $q->where('name', 'like', '%' . $search . '%');
//             });
//         }

//         // Urutkan berdasarkan created_at dan updated_at DESC
//         $query->orderByDesc('updated_at')->orderByDesc('created_at');

//         $roles = Role::whereIn('nama_role', ['kaprodi', 'kajur'])->get();

//         $dosenList = $query->paginate(10); // Bisa ubah jumlah per halaman

//         return view('admin.kelola-akun.dosen.views.kelolaAkunDosen', compact('dosenList', 'roles'));
//     }

//     public function getTotalAktif()
//     {
//         // Asumsi: Semua dosen yang ada di tabel 'dosen' dianggap aktif
//         // Jika ada kolom 'status' di tabel dosen, bisa filter: where('status', 'aktif')
//         $totalDosen = Dosen::count();

//         return response()->json([
//             'total' => $totalDosen
//         ]);
//     }

//     // Proses simpan dosen baru
//     public function store(Request $request)
//     {
//         $request->validate([
//             'nama' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email',
//             'nidn' => 'required|string|unique:dosen,nidn',
//             'password' => ['required', Password::min(8)],
//             'role_id' => 'nullable|in:2,3', // hanya kaprodi/kajur jika dipilih
//         ]);

//         DB::transaction(function () use ($request) {
//             // Buat akun user
//             $user = User::create([
//                 'name' => $request->nama,
//                 'email' => $request->email,
//                 'password' => Hash::make($request->password),
//             ]);

//             // Ambil ID role dosen (wajib)
//             $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();

//             // Inisialisasi role_ids dengan role dosen
//             $roleIds = [$roleDosen->id];

//             // Tambahkan kaprodi/kajur jika dipilih
//             if ($request->filled('role_id') && in_array($request->role_id, [2, 3])) {
//                 $roleIds[] = (int) $request->role_id;
//             }

//             // Simpan relasi ke tabel user_roles
//             $user->roles()->attach($roleIds);

//             // Simpan data dosen
//             Dosen::create([
//                 'user_id' => $user->id,
//                 'nidn' => $request->nidn,
//             ]);
//         });

//         return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil ditambahkan.');
//     }

//     public function update(Request $request, $id)
//     {
//         $dosen = Dosen::with('user.roles')->findOrFail($id);

//         $request->validate([
//             'nama' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email,' . $dosen->user->id,
//             'nidn' => 'required|string|unique:dosen,nidn,' . $dosen->id,
//             'password' => ['nullable', 'confirmed', Password::min(8)],
//             'role_id' => 'nullable|in:2,3',
//         ]);

//         DB::transaction(function () use ($request, $dosen) {
//             $user = $dosen->user;

//             // Update data user
//             $user->update([
//                 'name' => $request->nama,
//                 'email' => $request->email,
//                 'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
//             ]);

//             // Update data dosen
//             $dosen->update([
//                 'nidn' => $request->nidn,
//             ]);

//             // Siapkan role yang akan disimpan ulang
//             $roleIds = [];

//             // Pastikan role dosen selalu ada
//             $roleDosen = Role::where('nama_role', 'dosen')->firstOrFail();
//             $roleIds[] = $roleDosen->id;

//             // Tambahkan role kaprodi atau kajur jika dipilih
//             if ($request->filled('role_id')) {
//                 $roleIds[] = (int) $request->role_id;
//             }

//             // Sinkronisasi: hanya simpan role yang kita izinkan
//             $user->roles()->sync($roleIds);
//         });

//         return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil diperbarui.');
//     }

//     public function destroy($id)
//     {
//         $dosen = Dosen::with('user')->findOrFail($id);

//         // Hapus user & dosen
//         $dosen->user->delete();
//         $dosen->delete();

//         if (request()->ajax()) {
//             return response()->json(['message' => 'Akun dosen berhasil dihapus.']);
//         }

//         return redirect()->route('akun-dosen.kelola')->with('success', 'Akun dosen berhasil dihapus.');
//     }

//     // Add dashboard method to fix the error
//     public function dashboard()
//     {
//         return view('kaprodi.dashboard'); // Adjust the view path as needed
//     }
// }