<?php

namespace App\Http\Controllers\Dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BimbinganTA;
use App\Models\CatatanBimbinganTA;
use App\Models\PeranDosenTA;
use App\Models\TugasAkhir;
use App\Models\HistoryPerubahanJadwal;
use App\Models\Dosen;

class BimbinganMahasiswaController extends Controller
{
    public function dashboard(Request $request)
    {
        $dosen = \App\Models\Dosen::where('user_id', Auth::id())->firstOrFail();

        $query = PeranDosenTA::with(['tugasAkhir.mahasiswa.user'])
            ->where('dosen_id', $dosen->id)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2']);

        if ($request->filled('prodi')) {
            $query->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('prodi', $request->prodi));
        }

        if ($request->filled('search')) {
            $query->whereHas(
                'tugasAkhir.mahasiswa.user',
                fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            );
        }

        $mahasiswaList = $query->latest()->get();

        return view('dosen.bimbingan.dashboard.dashboard', compact('mahasiswaList'));
    }

    public function showDetail($id)
    {
        $ta = TugasAkhir::with(['mahasiswa.user', 'bimbingan.catatanBimbingan'])
            ->where('mahasiswa_id', $id)
            ->firstOrFail();

        $dosenId = \App\Models\Dosen::where('user_id', Auth::id())->firstOrFail()->id;

        $bimbinganList = $ta->bimbingan()
            ->where('dosen_id', $dosenId)
            ->latest()
            ->get();

        $revisiList = $ta->revisi()->latest()->get();

        return view('dosen.bimbingan.detail-bimbingan.detail', [
            'mahasiswa'     => $ta->mahasiswa,
            'tugasAkhir'    => $ta,
            'bimbinganList' => $bimbinganList,
            'revisiList'    => $revisiList,
        ]);
    }

    public function setujui($id)
    {
        $bimbingan = BimbinganTA::findOrFail($id);
        $bimbingan->status_bimbingan = 'disetujui';
        $bimbingan->save();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Bimbingan berhasil diterima.'
        ]);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'komentar_penolakan' => 'required|string|max:1000',
        ]);

        $bimbingan = BimbinganTA::findOrFail($id);
        $bimbingan->status_bimbingan = 'ditolak';
        $bimbingan->save();

        $bimbingan->catatanBimbingan()->create([
            'catatan'     => $request->komentar_penolakan,
            'author_type' => 'dosen',
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Ditolak!',
            'message' => 'Bimbingan berhasil ditolak dan alasan telah dikirim ke mahasiswa.'
        ]);
    }

    public function selesaiBimbingan($id)
    {
        $bimbingan = BimbinganTA::findOrFail($id);
        $bimbingan->status_bimbingan = 'selesai';
        $bimbingan->save();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Selesai!',
            'message' => 'Bimbingan telah ditandai selesai.'
        ]);
    }

    public function terimaJadwal($id)
    {
        $perubahan = HistoryPerubahanJadwal::findOrFail($id);
        $perubahan->status = 'disetujui';
        $perubahan->save();

        // Update juga jadwal bimbingan utama
        $bimbingan = $perubahan->bimbingan;
        if ($bimbingan) {
            $bimbingan->tanggal_bimbingan = $perubahan->tanggal_baru;
            $bimbingan->jam_bimbingan = $perubahan->jam_baru;
            $bimbingan->save();
        }

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Disetujui!',
            'message' => 'Perubahan jadwal disetujui dan jadwal telah diperbarui.'
        ]);
    }

    public function tolakJadwal(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        $perubahan = HistoryPerubahanJadwal::findOrFail($id);
        $perubahan->status = 'ditolak';
        $perubahan->save();

        $bimbingan = $perubahan->bimbingan;
        if ($bimbingan) {
            $bimbingan->catatanBimbingan()->create([
                'catatan'     => $request->komentar,
                'author_type' => 'dosen',
                'author_id'   => Auth::id(), // tambahkan ini
            ]);
        }

        return redirect()->back()->with('alert', [
            'type' => 'info',
            'title' => 'Ditolak',
            'message' => 'Pengajuan perubahan jadwal ditolak dengan catatan.'
        ]);
    }

    public function terimaPembatalanTugasAkhir($id)
    {
        $tugasAkhir = TugasAkhir::with('peranDosenTa')->findOrFail($id);

        // Cek status TA
        if ($tugasAkhir->status !== 'menunggu_pembatalan') {
            return back()->with('alert', [
                'type' => 'info',
                'title' => 'Tidak Dapat Diproses',
                'message' => 'Status TA bukan "menunggu pembatalan".'
            ]);
        }

        // Ambil dosen yang login
        $dosen = \App\Models\Dosen::where('user_id', Auth::id())->firstOrFail();

        // Pastikan dosen ini adalah pembimbing mahasiswa tersebut
        $peran = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $dosen->id)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->first();

        if (!$peran) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Bukan Pembimbing',
                'message' => 'Anda bukan pembimbing dari mahasiswa ini.'
            ]);
        }

        // Simpan persetujuan pembatalan
        $peran->update([
            'setuju_pembatalan' => 'ya', // fix: gunakan string sesuai enum
            'tanggal_verifikasi' => now(),
        ]);

        // Reload relasi agar data yang digunakan up-to-date
        $tugasAkhir->load('peranDosenTa');

        // Cek apakah semua pembimbing sudah setuju
        $setujuSemua = $tugasAkhir->peranDosenTa
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->every(fn($p) => $p->setuju_pembatalan === 'ya'); // fix: bandingkan string

        if ($setujuSemua) {
            // Update status TA
            $tugasAkhir->update(['status' => 'dibatalkan']);

            // Hapus relasi pembimbing
            $tugasAkhir->peranDosenTa()
                ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                ->delete();

            return back()->with('alert', [
                'type' => 'success',
                'title' => 'Dibatalkan!',
                'message' => 'TA telah dibatalkan dan pembimbing dihapus.'
            ]);
        }

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Verifikasi Tersimpan',
            'message' => 'Anda telah menyetujui pembatalan. Menunggu pembimbing lain.'
        ]);
    }

    public function tolakPembatalanTugasAkhir(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:1000',
        ]);

        $tugasAkhir = TugasAkhir::with('peranDosenTa')->findOrFail($id);

        if ($tugasAkhir->status !== 'menunggu_pembatalan') {
            return back()->with('alert', [
                'type' => 'info',
                'title' => 'Tidak Dapat Diproses',
                'message' => 'Status TA bukan "menunggu pembatalan".'
            ]);
        }

        $dosen = \App\Models\Dosen::where('user_id', Auth::id())->firstOrFail();

        if (!$dosen) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => 'Hanya dosen yang bisa menolak pembatalan.'
            ]);
        }

        // Pastikan dosen adalah pembimbing
        $peran = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $dosen->id)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->first();

        if (!$peran) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Bukan Pembimbing',
                'message' => 'Anda bukan pembimbing dari mahasiswa ini.'
            ]);
        }

        // Reset status TA ke sebelumnya (contoh: disetujui)
        $tugasAkhir->update([
            'status' => 'disetujui', // Sesuaikan dengan status sebelumnya jika disimpan
            'alasan_pembatalan' => null,
        ]);

        // Reset semua verifikasi pembatalan
        $tugasAkhir->peranDosenTa()
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->update([
                'setuju_pembatalan' => null,
                'tanggal_verifikasi' => null,
                'catatan_verifikasi' => null,
            ]);

        // Simpan catatan penolakan dari dosen
        $peran->update([
            'catatan_verifikasi' => $request->catatan_penolakan,
        ]);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Pembatalan Ditolak',
            'message' => 'Pengajuan pembatalan telah ditolak dan status TA dikembalikan.'
        ]);
    }
}
