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
        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir) {
            return view('mahasiswa.bimbingan.dashboard.dashboard', ['jadwals' => []])
                ->with('info', 'Anda belum memiliki data tugas akhir.');
        }

        // Ambil data bimbingan termasuk dosen, user dosen, dan catatan
        $jadwals = $tugasAkhir->bimbingan()
            ->with(['dosen.user', 'catatanBimbingan'])
            ->orderBy('tanggal_bimbingan', 'desc')
            ->orderBy('jam_bimbingan', 'asc') // Menambahkan urutan jam jika diperlukan
            ->get();

        return view('mahasiswa.bimbingan.dashboard.dashboard', compact('jadwals'));
    }

    public function ajukanJadwal()
    {
        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = $mahasiswa->tugasAkhir()->with('peranDosenTa.dosen.user')->first();
        if (!$tugasAkhir) {
            return redirect()->back()->with('error', 'Data tugas akhir belum tersedia.');
        }

        $dosenList = $tugasAkhir->peranDosenTa;
        $bimbinganCount = [];
        $statusBimbingan = [];
        $disabledPengajuan = [];

        foreach ($dosenList as $peran) {
            $dosenId = $peran->dosen_id;

            // Hitung sesi terakhir per dosen (bukan status selesai lagi)
            $jumlah = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->max('sesi_ke') ?? 0;
            $bimbinganCount[$dosenId] = $jumlah;

            // Ambil status terakhir
            $last = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->orderByDesc('created_at')
                ->first();
            $status = $last?->status_bimbingan;
            $statusBimbingan[$dosenId] = $status ?? '-';

            // Logika pengajuan
            if ($jumlah >= 9) {
                $disabled = true; // Sudah maksimal 9 sesi
            } else {
                // Cek apakah masih ada pengajuan berjalan (status diajukan)
                $adaPengajuanBerjalan = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                    ->where('dosen_id', $dosenId)
                    ->where('status_bimbingan', 'diajukan')
                    ->exists();

                $disabled = $adaPengajuanBerjalan;
            }

            $disabledPengajuan[$dosenId] = $disabled;
        }

        return view('mahasiswa.bimbingan.views.ajukanBimbingan', compact(
            'tugasAkhir',
            'dosenList',
            'bimbinganCount',
            'statusBimbingan',
            'disabledPengajuan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'tipe_dospem' => 'required|in:1,2',
            'tanggal_jadwal' => 'required|date',
            'waktu_jadwal' => 'required|date_format:H:i',
            'catatan' => 'nullable|string',
        ]);

        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir) {
            return back()->withErrors(['error' => 'Tugas Akhir belum ada.']);
        }

        // Hitung sesi terakhir dosen ini
        $lastSesi = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->max('sesi_ke') ?? 0;

        if ($lastSesi >= 9) {
            return back()->withErrors(['error' => 'Bimbingan sudah mencapai batas maksimal 9 sesi.']);
        }

        // Cek apakah masih ada pengajuan berjalan
        $adaPengajuanBerjalan = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->where('status_bimbingan', 'diajukan')
            ->exists();

        if ($adaPengajuanBerjalan) {
            return back()->withErrors(['error' => 'Masih ada pengajuan bimbingan yang sedang diproses.']);
        }

        // Buat pengajuan baru
        $bimbingan = BimbinganTa::create([
            'tugas_akhir_id' => $tugasAkhir->id,
            'dosen_id' => $request->dosen_id,
            'peran' => $request->tipe_dospem == 1 ? 'pembimbing1' : 'pembimbing2',
            'sesi_ke' => $lastSesi + 1,
            'tanggal_bimbingan' => $request->tanggal_jadwal,
            'jam_bimbingan' => $request->waktu_jadwal,
            'catatan' => $request->catatan,
            'status_bimbingan' => 'diajukan',
        ]);

        if ($request->filled('catatan')) {
            CatatanBimbingan::create([
                'bimbingan_ta_id' => $bimbingan->id,
                'author_type' => 'mahasiswa',
                'author_id' => $mahasiswa->id,
                'catatan' => $request->catatan,
            ]);
        }

        return redirect()->route('bimbingan.ajukanJadwal')->with('success', 'Jadwal bimbingan berhasil diajukan.');
    }

    public function ubahJadwal(Request $request, $id)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'waktu_jadwal' => 'required|date_format:H:i',
            'catatan' => 'nullable|string',
        ]);

        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        $jadwal = BimbinganTa::findOrFail($id);

        if ($jadwal->tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah jadwal ini.');
        }

        if ($jadwal->status_bimbingan !== 'diajukan') {
            return back()->withErrors(['error' => 'Jadwal ini tidak bisa diubah karena sudah diproses dosen.']);
        }

        $jadwal->update([
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'jam_bimbingan' => $request->waktu_jadwal,
        ]);

        if ($request->filled('catatan')) {
            $catatan = CatatanBimbingan::firstOrCreate(
                [
                    'bimbingan_ta_id' => $jadwal->id,
                    'author_type' => 'mahasiswa',
                    'author_id' => $mahasiswa->id,
                ],
                ['catatan' => $request->catatan]
            );

            $catatan->update(['catatan' => $request->catatan]);
        }

        return redirect()->back()->with('success', 'Perubahan jadwal telah diajukan ke dosen.');
    }
}
