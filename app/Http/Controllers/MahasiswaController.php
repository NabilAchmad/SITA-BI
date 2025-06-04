<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\JadwalSidang;
// use App\Models\Pengumuman;
use App\Models\JudulTA;
use App\Models\Sidang;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pengumuman;

class MahasiswaController extends Controller
{
    // Tampilkan mahasiswa semua list mahasiswa
    public function listMahasiswa(Request $request)
    {
        $query = Mahasiswa::with('user');

        // Filter berdasarkan prodi (D3 / D4) dari prodi
        if ($request->filled('prodi')) {
            $prodi = $request->prodi;
            $query->where('prodi', 'LIKE', $prodi . '%');
        }

        // Pencarian berdasarkan nama atau nim
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $mahasiswa = $query->paginate(10);

        // Jika AJAX request (realtime pencarian)
        if ($request->ajax()) {
            return view('admin.kelola-akun.mahasiswa.crud-mahasiswa.read', compact('mahasiswa'))->render();
        }

        return view('admin.kelola-akun.mahasiswa.views.kelolaMahasiswa', compact('mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user->id,
            'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'prodi' => 'required|string|max:100',
            'password' => 'nullable|confirmed|min:8',
        ]);

        // Update user data
        $mahasiswa->user->update([
            'name' => $request->name,
            'email' => $request->email,
            // update password hanya jika ada isian
            'password' => $request->filled('password') ? bcrypt($request->password) : $mahasiswa->user->password,
        ]);

        // Update mahasiswa data
        $mahasiswa->update([
            'nim' => $request->nim,
            'prodi' => $request->prodi,
        ]);

        return redirect()->route('akun-mahasiswa.kelola')->with('success', 'Data mahasiswa berhasil diperbarui.');
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

    public function dashboard()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        $pengumuman = Pengumuman::latest()->take(5)->get();

        $judulTA = JudulTA::where('mahasiswa_id', $mahasiswa->id)->first();

        $sidang = Sidang::where('tugas_akhir_id', $judulTA ? $judulTA->id : null)->first();

        $jadwal = JadwalSidang::where('sidang_id', $sidang ? $sidang->id : null)->get();

        $nilai = Nilai::whereHas('sidang', function ($query) use ($sidang) {
            $query->where('id', $sidang ? $sidang->id : 0);
        })->get();

        return view('mahasiswa.views.dashboard', compact('pengumuman', 'jadwal', 'judulTA', 'sidang', 'nilai'));
    }

    // API methods for mahasiswa

    public function apiIndex()
    {
        $mahasiswa = Mahasiswa::with('user')->get();
        return response()->json($mahasiswa);
    }

    public function apiShow($id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
        return response()->json($mahasiswa);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'prodi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'phone' => $request->phone,
            'address' => $request->address,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
        ]);

        return response()->json($mahasiswa, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $mahasiswa->user->id,
            'nim' => 'sometimes|required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'prodi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user
        if ($request->has('name') || $request->has('email') || $request->filled('password')) {
            $mahasiswa->user->update([
                'name' => $request->name ?? $mahasiswa->user->name,
                'email' => $request->email ?? $mahasiswa->user->email,
                'password' => $request->filled('password') ? bcrypt($request->password) : $mahasiswa->user->password,
            ]);
        }

        // Update mahasiswa
        $mahasiswa->update([
            'nim' => $request->nim ?? $mahasiswa->nim,
            'phone' => $request->phone ?? $mahasiswa->phone,
            'address' => $request->address ?? $mahasiswa->address,
            'prodi' => $request->prodi ?? $mahasiswa->prodi,
            'angkatan' => $request->angkatan ?? $mahasiswa->angkatan,
        ]);

        return response()->json($mahasiswa);
    }

    public function apiDestroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        // Delete related user first
        $user = $mahasiswa->user;
        $mahasiswa->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json(['message' => 'Mahasiswa deleted successfully']);
    }
}
