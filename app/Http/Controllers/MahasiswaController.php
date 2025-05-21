<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\JudulTA;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Sidang;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Fetch mahasiswa related data
        $jadwal = Jadwal::where('mahasiswa_id', $user->id)->get();
        $judulTA = JudulTA::where('mahasiswa_id', $user->id)->first();
        $nilai = Nilai::where('mahasiswa_id', $user->id)->get();
        $pengumuman = Pengumuman::orderBy('tanggal', 'desc')->limit(5)->get();
        $sidang = Sidang::where('mahasiswa_id', $user->id)->first();

        return view('mahasiswa.dashboard', compact('jadwal', 'judulTA', 'nilai', 'pengumuman', 'sidang'));
=======
use App\Models\Mahasiswa;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

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
>>>>>>> a3c877002252bd25be5c9a61c70e7da7ecab77c6
    }
}
