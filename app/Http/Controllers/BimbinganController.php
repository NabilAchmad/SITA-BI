<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\PeranDosenTa;

class BimbinganController extends Controller
{
    private function assumedMahasiswaId()
    {
        // Ganti ini sesuai ID mahasiswa yang ada di tabel users
        return \App\Models\User::find(1);
    }

    public function dashboard()
    {
        // Logika untuk menampilkan dashboard bimbingan
        return view('mahasiswa.bimbingan.dashboard.dashboard');
    }

    public function ajukanJadwal()
    {
        $mahasiswa = $this->assumedMahasiswaId()->mahasiswa;

        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->with(['pembimbing1.dosen.user', 'pembimbing2.dosen.user'])
            ->first();

        $dospem1 = $tugasAkhir?->pembimbing1?->dosen;
        $dospem2 = $tugasAkhir?->pembimbing2?->dosen;

        $dosenList = PeranDosenTa::with(['user', 'pembimbing1', 'pembimbing2'])
            ->paginate(10);

        return view('mahasiswa.bimbingan.views.ajukanBimbingan', compact('dospem1', 'dospem2', 'dosenList'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'dospem_ke' => 'required|in:1,2',
        ]);

        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;
        $ta = $mahasiswa->tugasAkhir;

        if (!$ta) {
            return back()->with('error', 'Tugas Akhir tidak ditemukan.');
        }

        if ($request->dospem_ke == 2) {
            // pastikan bimbingan dospem 1 sudah acc
            $accDospem1 = $ta->bimbingan()
                ->where('dospem_ke', 1)
                ->where('status', 'acc')
                ->exists();

            if (!$accDospem1) {
                return back()->with('error', 'Anda belum menyelesaikan bimbingan dengan dospem 1.');
            }
        }

        $ta->bimbingan()->create([
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'dospem_ke' => $request->dospem_ke,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Jadwal bimbingan berhasil diajukan.');
    }

    public function jadwalBimbingan()
    {
        // Logika untuk menampilkan jadwal bimbingan
        return view('mahasiswa.bimbingan.views.lihatJadwal');
    }

    public function ubahJadwal()
    {
        return view('mahasiswa.bimbingan.views.perubahanJadwal');
    }
}
