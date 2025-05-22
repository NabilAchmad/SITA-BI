<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\JadwalSidang;
use App\Models\Pengumuman;
use App\Models\JudulTA;
use App\Models\Sidang;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    // Tampilkan mahasiswa yang sudah memiliki 2 pembimbing
    public function index()
    {
        $mahasiswa = Mahasiswa::whereHas('tugasAkhir.peranDosenTa', function ($q) {
            $q->whereIn('peran', ['pembimbing1', 'pembimbing2']);
        })->with([
            'user',
            'tugasAkhir.peranDosenTa.dosen.user'  // eager loading sampai nama dosen
        ])->get();

        return view('admin.mahasiswa.views.list-mhs', compact('mahasiswa'));
    }

    /**
     * API endpoint to get list of mahasiswa in JSON format
     */
    public function apiIndex()
    {
        $mahasiswa = Mahasiswa::with('user')->get();
        return response()->json([
            'status' => 'success',
            'data' => $mahasiswa
        ]);
    }

    // Tampilkan mahasiswa semua list mahasiswa
    public function listMahasiswa()
    {
        $mahasiswa = Mahasiswa::with([
            'user',
        ])->get();
        return view('admin.kelola-akun.mahasiswa.views.kelolaMahasiswa', compact('mahasiswa'));
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        return view('admin.kelola-akun.mahasiswa.views.editMahasiswa', compact('mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user->id,
            'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
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
        ]);

        return redirect()->route('akun-mahasiswa.kelola')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function mahasiswaBelumPunyaJadwal()
    {
        $mahasiswa = Mahasiswa::whereHas('tugasAkhir.sidang', function ($q) {
            $q->where('status', 'dijadwalkan')
                ->whereDoesntHave('jadwalSidang'); // Belum dijadwalkan
        })->with(['user', 'tugasAkhir.sidang'])->get();

        return view('admin.sidang.mahasiswa.views.read-mhs-sidang', compact('mahasiswa'));
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

        return view('mahasiswa.dashboard', compact('pengumuman', 'jadwal', 'judulTA', 'sidang', 'nilai'));
    }
}
