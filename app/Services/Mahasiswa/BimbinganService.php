<?php

namespace App\Services\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use App\Models\HistoryPerubahanJadwal;

class BimbinganService
{
    protected function abortJikaTADibatalkan($tugasAkhir)
    {
        if ($tugasAkhir->status === 'dibatalkan') {
            abort(403, 'Tugas Akhir Anda telah dibatalkan.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) abort(404, 'Data mahasiswa tidak ditemukan.');

        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir) {
            return redirect()->route('mahasiswa.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Data Tidak Ditemukan',
                'text' => 'Data tugas akhir belum tersedia.'
            ]);
        }

        if (!in_array($tugasAkhir->status, ['disetujui', 'draft', 'menunggu_pembatalan'])) {
            return view('mahasiswa.bimbingan.dashboard.dashboard', [
                'tugasAkhir' => $tugasAkhir,
                'jadwals' => collect(),
            ])->with('alert', [
                'type' => 'info',
                'title' => 'Status Tidak Sesuai',
                'text' => 'Tugas Akhir Anda belum dapat dibimbing karena status saat ini: ' . $tugasAkhir->status
            ]);
        }

        $pembimbingAda = $tugasAkhir->peranDosenTa()
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->exists();

        if (!$pembimbingAda) {
            return view('mahasiswa.bimbingan.dashboard.dashboard', [
                'tugasAkhir' => $tugasAkhir,
                'jadwals' => collect(),
            ])->with('alert', [
                'type' => 'info',
                'title' => 'Belum Ada Pembimbing',
                'text' => 'Belum ada dosen pembimbing yang ditetapkan.'
            ]);
        }

        $jadwals = $tugasAkhir->bimbinganTa()
            ->with(['dosen.user', 'catatanBimbingan', 'historyPerubahan' => fn($q) => $q->latest()])
            ->orderBy('tanggal_bimbingan', 'desc')
            ->orderBy('jam_bimbingan', 'asc')
            ->get();

        return view('mahasiswa.bimbingan.dashboard.dashboard', compact('jadwals', 'tugasAkhir'));
    }

    public function ajukanJadwal()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->with(['peranDosenTa' => fn($q) => $q->whereIn('peran', ['pembimbing1', 'pembimbing2'])])
            ->first();

        if (!$tugasAkhir) {
            return redirect()->route('mahasiswa.bimbingan.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Pengajuan Gagal',
                'text' => 'Data tugas akhir belum tersedia.'
            ]);
        }

        $this->abortJikaTADibatalkan($tugasAkhir);

        if (!in_array($tugasAkhir->status, ['disetujui', 'revisi', 'menunggu_pembatalan'])) {
            return redirect()->route('mahasiswa.bimbingan.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Pengajuan Ditolak',
                'text' => 'Status tugas akhir Anda tidak memenuhi syarat untuk bimbingan.'
            ]);
        }

        if (empty($tugasAkhir->file_path)) {
            return redirect()->route('mahasiswa.bimbingan.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Pengajuan Ditolak',
                'text' => 'Upload file proposal sebelum bimbingan!'
            ]);
        }

        $dosenList = $tugasAkhir->peranDosenTa;
        $bimbinganCount = [];
        $statusBimbingan = [];
        $disabledPengajuan = [];

        foreach ($dosenList as $peran) {
            $dosenId = $peran->dosen_id;

            $jumlah = BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->max('sesi_ke') ?? 0;

            $bimbinganCount[$dosenId] = $jumlah;

            $last = BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->orderByDesc('created_at')
                ->first();

            $statusBimbingan[$dosenId] = $last?->status_bimbingan ?? '-';

            $disabledPengajuan[$dosenId] = $jumlah >= 9 || BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
                ->where('dosen_id', $dosenId)
                ->where('status_bimbingan', 'diajukan')
                ->exists();
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

        $mahasiswa = Auth::user()->mahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        if (!$tugasAkhir || empty($tugasAkhir->file_path)) {
            return back()->withErrors(['error' => 'Tugas Akhir belum valid untuk diajukan bimbingan.']);
        }

        $this->abortJikaTADibatalkan($tugasAkhir);

        if (!in_array($tugasAkhir->status, ['disetujui', 'revisi', 'menunggu_pembatalan'])) {
            return back()->withErrors(['error' => 'Status tugas akhir Anda tidak memenuhi syarat untuk bimbingan.']);
        }

        $adaPengajuanBerjalan = BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->where('status_bimbingan', 'diajukan')
            ->exists();

        if ($adaPengajuanBerjalan) {
            return back()->withErrors(['error' => 'Masih ada pengajuan bimbingan yang sedang diproses.']);
        }

        $sesiCount = BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('dosen_id', $request->dosen_id)
            ->where('status_bimbingan', 'selesai')
            ->count();

        if ($sesiCount >= 9) {
            return back()->withErrors(['error' => 'Bimbingan sudah mencapai batas maksimal 9 sesi.']);
        }

        $bimbingan = BimbinganTA::create([
            'tugas_akhir_id' => $tugasAkhir->id,
            'dosen_id' => $request->dosen_id,
            'peran' => $request->tipe_dospem == 1 ? 'pembimbing1' : 'pembimbing2',
            'tanggal_bimbingan' => $request->tanggal_jadwal,
            'jam_bimbingan' => $request->waktu_jadwal,
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

        return redirect()->route('bimbingan.ajukanJadwal')->with('alert', [
            'type' => 'success',
            'title' => 'Pengajuan Berhasil',
            'text' => 'Jadwal bimbingan berhasil diajukan.'
        ]);
    }

    public function ubahJadwal(Request $request, $id)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'jam_bimbingan' => 'required|date_format:H:i',
            'catatan' => 'nullable|string',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $jadwal = BimbinganTA::findOrFail($id);

        if ($jadwal->tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Tidak punya akses ke jadwal ini.');
        }

        $this->abortJikaTADibatalkan($jadwal->tugasAkhir);

        if ($jadwal->status_bimbingan !== 'diajukan') {
            return back()->withErrors(['error' => 'Jadwal ini tidak bisa diubah karena sudah diproses.']);
        }

        $sudahAdaPengajuan = HistoryPerubahanJadwal::where('bimbingan_ta_id', $jadwal->id)
            ->where('status', 'menunggu')
            ->exists();

        if ($sudahAdaPengajuan) {
            return back()->withErrors(['error' => 'Pengajuan sebelumnya masih menunggu tanggapan.']);
        }

        HistoryPerubahanJadwal::create([
            'bimbingan_ta_id' => $jadwal->id,
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_lama' => $jadwal->tanggal_bimbingan,
            'jam_lama' => $jadwal->jam_bimbingan,
            'tanggal_baru' => $request->tanggal_bimbingan,
            'jam_baru' => $request->jam_bimbingan,
            'alasan_perubahan' => $request->catatan,
            'status' => 'menunggu',
        ]);

        $jadwal->update([
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'jam_bimbingan' => $request->jam_bimbingan,
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Perubahan Jadwal',
            'text' => 'Pengajuan perubahan jadwal berhasil dikirim.'
        ]);
    }
}
