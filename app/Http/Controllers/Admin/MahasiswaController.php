<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
