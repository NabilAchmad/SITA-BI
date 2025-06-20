<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\{TugasAkhir, Mahasiswa, BimbinganTA, RevisiTa, DokumenTa, Sidang, File};
use Illuminate\Support\Facades\{Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class TugasAkhirController extends Controller
{
    private function assumedMahasiswa()
    {
        return Mahasiswa::where('user_id', 2)->firstOrFail();
    }

    public function dashboard()
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = $mahasiswa->tugasAkhir;
        $sudahMengajukan = $tugasAkhir !== null;

        return view('mahasiswa.tugas-akhir.dashboard.dashboard', compact('tugasAkhir', 'sudahMengajukan', 'mahasiswa'));
    }

    public function ajukanForm()
    {
        $mahasiswa = $this->assumedMahasiswa();

        if ($mahasiswa->tugasAkhir) {
            return redirect()->route('tugas-akhir.dashboard')->withErrors(['error' => 'Anda sudah mengajukan tugas akhir.']);
        }

        return view('mahasiswa.tugas-akhir.crud-ta.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $mahasiswa = $this->assumedMahasiswa();

        if ($mahasiswa->tugasAkhir) {
            return redirect()->route('tugas-akhir.dashboard')->withErrors(['error' => 'Anda sudah mengajukan tugas akhir.']);
        }

        $request->validate([
            'judul' => 'required|string|max:255|unique:tugas_akhir,judul,NULL,id,mahasiswa_id,' . $mahasiswa->id,
            'abstrak' => 'required|string',
        ]);

        TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'status' => 'diajukan',
            'tanggal_pengajuan' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('tugas-akhir.dashboard')->with('success', 'Tugas Akhir berhasil diajukan!');
    }

    public function progress()
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->with(['peranDosenTa' => function ($query) {
                $query->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                    ->with('dosen.user');
            }])->first();

        $isMengajukanTA = $tugasAkhir && in_array($tugasAkhir->status, [
            'diajukan',
            'revisi',
            'disetujui',
            'lulus_tanpa_revisi',
            'draft',
            'menunggu_pembatalan'
        ]);

        $progressBimbingan = $tugasAkhir
            ? BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
            ->latest('tanggal_bimbingan')
            ->get()
            : collect();

        $revisi = $tugasAkhir
            ? RevisiTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get()
            : collect();

        $dokumen = $tugasAkhir
            ? DokumenTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get()
            : collect();

        $sidang = $tugasAkhir
            ? Sidang::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get()
            : collect();

        $jumlahBimbingan = $progressBimbingan->count();

        $progress = match ($tugasAkhir?->status) {
            'diajukan' => min(ceil(($jumlahBimbingan / 7) * 100), 49),
            'disetujui' => 50 + min(ceil(($jumlahBimbingan / 7) * 50), 49),
            'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 100,
            'draft', 'menunggu_pembatalan' => 0,
            default => 0,
        };

        $pembimbingList = $tugasAkhir?->peranDosenTa ?? collect();

        return view('mahasiswa.tugas-akhir.crud-ta.progress', compact(
            'tugasAkhir',
            'progressBimbingan',
            'revisi',
            'dokumen',
            'sidang',
            'isMengajukanTA',
            'progress',
            'pembimbingList'
        ));
    }

    public function cancel(Request $request, $id)
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = TugasAkhir::with('peranDosenTa')->findOrFail($id);

        if ($tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan tugas akhir ini.');
        }

        // Jika status sudah draft, tidak perlu diproses ulang
        if ($tugasAkhir->status === 'draft') {
            return back()->with('info', 'Tugas akhir Anda sudah berstatus draft.');
        }

        // Set status menjadi "menunggu_pembatalan"
        $tugasAkhir->update([
            'status' => 'menunggu_pembatalan',
            'alasan_pembatalan' => $request->input('alasan'),
        ]);

        // Kosongkan status setuju_pembatalan semua pembimbing (jika pernah diajukan sebelumnya)
        foreach ($tugasAkhir->peranDosenTa as $peran) {
            if (in_array($peran->peran, ['pembimbing1', 'pembimbing2'])) {
                $peran->update([
                    'setuju_pembatalan' => null,
                    'tanggal_verifikasi' => null,
                    'catatan_verifikasi' => null,
                ]);
            }
        }

        return redirect()->route('tugas-akhir.progress')->with('success', 'Pengajuan pembatalan Tugas Akhir telah dikirim dan menunggu verifikasi dosen pembimbing.');
    }

    public function update(Request $request, $id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);
        $mahasiswa = $this->assumedMahasiswa();

        if ($tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file_proposal')) {
            if ($tugasAkhir->file_path) Storage::disk('public')->delete($tugasAkhir->file_path);
            $path = $request->file('file_proposal')->storeAs('proposal_ta', time() . '_' . Str::slug($request->file('file_proposal')->getClientOriginalName(), '_'), 'public');
            File::create([
                'file_path' => $path,
                'file_type' => $request->file('file_proposal')->getClientMimeType(),
                'uploaded_by' => $mahasiswa->id,
            ]);
            $tugasAkhir->file_path = $path;
        }

        $tugasAkhir->update(['judul' => $request->judul, 'abstrak' => $request->abstrak]);

        return redirect()->route('tugasAkhir.progress')->with('success', 'Tugas Akhir berhasil diperbarui!');
    }

    public function showCancelled()
    {
        $mahasiswa = $this->assumedMahasiswa();

        // Ambil TA yang pembatalannya diajukan
        $tugasAkhirDibatalkan = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['menunggu_pembatalan', 'dibatalkan']) // tergantung logika
            ->latest()
            ->get();

        return view('mahasiswa.tugas-akhir.crud-ta.cancel', compact('tugasAkhirDibatalkan'));
    }
}
