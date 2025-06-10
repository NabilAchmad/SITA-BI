<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\PeranDosenTa;
use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use Illuminate\Http\Request;

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
        // Ambil user yang login, diasumsikan role-nya mahasiswa
        $user =  $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil Tugas Akhir dengan relasi peran dosen dan user dosen
        $tugasAkhir = $mahasiswa->tugasAkhir()->with('peranDosenTa.dosen.user')->first();

        if (!$tugasAkhir) {
            return redirect()->back()->with('error', 'Data tugas akhir belum tersedia.');
        }

        // Ambil dospem 1 dan dospem 2 berdasarkan peran
        $dospem1 = $tugasAkhir->peranDosenTa->firstWhere('peran', 'pembimbing1')?->dosen;
        $dospem2 = $tugasAkhir->peranDosenTa->firstWhere('peran', 'pembimbing2')?->dosen;

        // Ambil daftar dosen pembimbing dari peran_dosen_ta
        $dosenList = $tugasAkhir->peranDosenTa; // sudah include dosen dan user

        // Hitung jumlah bimbingan dengan setiap dosen
        $bimbinganCount = [];

        foreach ($dosenList as $peran) {
            if ($peran->dosen_id) {
                $jumlah = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                    ->where('dosen_id', $peran->dosen_id)
                    ->where('status_bimbingan', 'selesai') // hanya hitung yang sudah terjadwal
                    ->count();

                $bimbinganCount[$peran->dosen_id] = $jumlah;
            }
        }

        $statusBimbingan = [];
        foreach ($dosenList as $peran) {
            $lastBimbingan = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $peran->dosen_id)
                ->orderByDesc('created_at')
                ->first();

            $statusBimbingan[$peran->dosen_id] = $lastBimbingan?->status_bimbingan; // bisa null jika belum ada
        }

        return view('mahasiswa.bimbingan.views.ajukanBimbingan', compact(
            'tugasAkhir',
            'dosenList',
            'dospem1',
            'dospem2',
            'bimbinganCount',
            'statusBimbingan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'tipe_dospem' => 'required|in:1,2',
            'tanggal_jadwal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir) {
            return back()->withErrors(['error' => 'Tugas Akhir belum ada.']);
        }

        $bimbingan = BimbinganTa::create([
            'tugas_akhir_id' => $tugasAkhir->id,
            'dosen_id' => $request->dosen_id,
            'tanggal_bimbingan' => $request->tanggal_jadwal,
            'catatan' => '-', // kolom ini bisa tetap ada kosong / tanda strip
            'status_bimbingan' => 'diajukan',
        ]);

        if ($request->catatan) {
            CatatanBimbingan::create([
                'bimbingan_ta_id' => $bimbingan->id,
                'author_type' => 'mahasiswa',
                'author_id' => $mahasiswa->id,
                'catatan' => $request->catatan,
            ]);
        }

        return redirect()->route('bimbingan.ajukanJadwal')->with('success', 'Jadwal bimbingan berhasil diajukan.');
    }

    public function jadwalBimbingan()
    {
        // Ambil user yang login (mahasiswa)
        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil tugas akhir mahasiswa beserta jadwal bimbingan dengan relasi dosen dan user dosen
        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir) {
            return view('mahasiswa.bimbingan.views.lihatJadwal', ['jadwals' => []])
                ->with('info', 'Anda belum memiliki data tugas akhir.');
        }

        // Ambil jadwal bimbingan beserta data dosen dan user dosen
        $jadwals = $tugasAkhir->bimbingan()
            ->with(['dosen.user', 'catatanBimbingan'])
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('mahasiswa.bimbingan.views.lihatJadwal', compact('jadwals'));
    }

    public function ubahJadwal()
    {
        return view('mahasiswa.bimbingan.views.perubahanJadwal');
    }
}
