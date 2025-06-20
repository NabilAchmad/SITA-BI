<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\PeranDosenTa;
use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BimbinganController extends Controller
{
    private function assumedMahasiswaId()
    {
        // Ganti ini sesuai ID mahasiswa yang ada di tabel users
        return \App\Models\User::find(2);
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
            return view('mahasiswa.bimbingan.dashboard.dashboard', [
                'tugasAkhir' => null,
                'jadwals' => collect(),
            ])->with('info', 'Anda belum memiliki data tugas akhir.');
        }

        // Cek status TA apakah boleh bimbingan
        $statusValid = in_array($tugasAkhir->status, ['disetujui', 'draft']);
        if (!$statusValid) {
            return view('mahasiswa.bimbingan.dashboard.dashboard', [
                'tugasAkhir' => $tugasAkhir,
                'jadwals' => collect(),
            ])->with('info', 'Tugas Akhir Anda belum dapat dibimbing karena status saat ini: ' . $tugasAkhir->status);
        }

        // Cek apakah sudah punya pembimbing
        $pembimbingAda = $tugasAkhir->peranDosenTa()
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->exists();

        if (!$pembimbingAda) {
            return view('mahasiswa.bimbingan.dashboard.dashboard', [
                'tugasAkhir' => $tugasAkhir,
                'jadwals' => collect(),
            ])->with('info', 'Belum ada dosen pembimbing yang ditetapkan.');
        }

        // Ambil data bimbingan
        $jadwals = $tugasAkhir->bimbingan()
            ->with(['dosen.user', 'catatanBimbingan'])
            ->orderBy('tanggal_bimbingan', 'desc')
            ->orderBy('jam_bimbingan', 'asc')
            ->get();

        return view('mahasiswa.bimbingan.dashboard.dashboard', compact('jadwals', 'tugasAkhir'));
    }

    public function ajukanJadwal()
    {
        $user = $this->assumedMahasiswaId();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->with(['peranDosenTa' => function ($query) {
                $query->whereIn('peran', ['pembimbing1', 'pembimbing2']);
            }, 'peranDosenTa.dosen.user'])
            ->first();

        if (!$tugasAkhir) {
            return redirect()->back()->with('error', 'Data tugas akhir belum tersedia.');
        }

        if (!in_array($tugasAkhir->status, ['disetujui', 'revisi', 'menunggu_pembatalan'])) {
            return redirect()->back()->with('error', 'Status tugas akhir Anda tidak memenuhi syarat untuk bimbingan.');
        }

        if (empty($tugasAkhir->file_path)) {
            return redirect()->back()->with('error', 'Silakan upload file proposal terlebih dahulu sebelum mengajukan bimbingan.');
        }

        $dosenList = $tugasAkhir->peranDosenTa;
        $bimbinganCount = [];
        $statusBimbingan = [];
        $disabledPengajuan = [];

        foreach ($dosenList as $peran) {
            $dosenId = $peran->dosen_id;

            $jumlah = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->max('sesi_ke') ?? 0;
            $bimbinganCount[$dosenId] = $jumlah;

            $last = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->orderByDesc('created_at')
                ->first();
            $statusBimbingan[$dosenId] = $last?->status_bimbingan ?? '-';

            $disabled = $jumlah >= 9 || BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->where('status_bimbingan', 'diajukan')
                ->exists();

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

        if (!$tugasAkhir || empty($tugasAkhir->file_path) || !in_array($tugasAkhir->status, ['disetujui', 'revisi', 'menunggu_pembatalan'])) {
            return back()->withErrors(['error' => 'Tugas Akhir belum valid untuk diajukan bimbingan.']);
        }

        $lastSesi = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->max('sesi_ke') ?? 0;

        if ($lastSesi >= 9) {
            return back()->withErrors(['error' => 'Bimbingan sudah mencapai batas maksimal 9 sesi.']);
        }

        $adaPengajuanBerjalan = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->where('status_bimbingan', 'diajukan')
            ->exists();

        if ($adaPengajuanBerjalan) {
            return back()->withErrors(['error' => 'Masih ada pengajuan bimbingan yang sedang diproses.']);
        }

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
