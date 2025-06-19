<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{TugasAkhir, Mahasiswa, BimbinganTA, RevisiTa, DokumenTa, Sidang, File};
use Illuminate\Support\Facades\{Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;

class TugasAkhirController extends Controller
{
    private function assumedMahasiswa()
    {
        return Mahasiswa::where('user_id', 20)->firstOrFail();
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
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $isMengajukanTA = $tugasAkhir && in_array($tugasAkhir->status, ['diajukan', 'revisi', 'disetujui', 'lulus_tanpa_revisi', 'draft']);

        $progressBimbingan = $tugasAkhir ? BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)->latest('tanggal_bimbingan')->get() : collect();
        $revisi = $tugasAkhir ? RevisiTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get() : collect();
        $dokumen = $tugasAkhir ? DokumenTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get() : collect();
        $sidang = $tugasAkhir ? Sidang::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get() : collect();

        $jumlahBimbingan = $progressBimbingan->count();
        $progress = match ($tugasAkhir?->status) {
            'diajukan' => min(ceil(($jumlahBimbingan / 7) * 100), 49),
            'disetujui' => 50 + min(ceil(($jumlahBimbingan / 7) * 50), 49),
            'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 100,
            'draft' => 0,
            default => 0,
        };

        return view('mahasiswa.tugas-akhir.crud-ta.progress', compact('tugasAkhir', 'progressBimbingan', 'revisi', 'dokumen', 'sidang', 'isMengajukanTA', 'progress'));
    }

    public function cancel(Request $request, $id)
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = TugasAkhir::findOrFail($id);

        if ($tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan tugas akhir ini.');
        }

        $tugasAkhir->update([
            'status' => 'draft',
            'alasan_pembatalan' => $request->input('alasan'),
        ]);

        return redirect()->route('tugas-akhir.progress')->with('success', 'Tugas Akhir berhasil dibatalkan.');
    }

    public function edit($id)
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = TugasAkhir::findOrFail($id);

        if ($tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        return view('mahasiswa.tugas-akhir.edit', compact('tugasAkhir'));
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

    public function destroy($id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswa()->id) {
            return back()->with('error', 'Tidak diizinkan.');
        }

        $tugasAkhir->delete();

        return redirect()->route('tugasAkhir.progress')->with('success', 'Tugas Akhir berhasil dibatalkan.');
    }

    public function showCancelled()
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhirDibatalkan = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'draft')
            ->latest()
            ->get();

        return view('mahasiswa.tugas-akhir.crud-ta.cancel', compact('tugasAkhirDibatalkan'));
    }
}
