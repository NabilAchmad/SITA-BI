<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasAkhir;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TugasAkhirController extends Controller
{
    private function assumedMahasiswaId()
    {
        // Ganti ini sesuai ID mahasiswa yang ada di tabel users
        return 3;
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Simpan file proposal
        $file = $request->file('file_proposal');
        $fileName = time() . '_' . Str::slug($file->getClientOriginalName(), '_');
        $path = $file->storeAs('proposal_ta', $fileName, 'public');

        // Simpan ke tabel files
        $fileModel = File::create([
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'uploaded_by' => $this->assumedMahasiswaId(),
        ]);

        // Simpan ke tabel tugas_akhir
        TugasAkhir::create([
            'mahasiswa_id' => $this->assumedMahasiswaId(),
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'file_path' => $path,
            'status' => 'diajukan',
            'tanggal_pengajuan' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Tugas Akhir berhasil diajukan!');
    }

    public function progress()
    {
        // Hanya menampilkan tugas akhir yang belum dibatalkan
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $this->assumedMahasiswaId())
            ->whereNull('deleted_at') // Filter untuk tidak menampilkan yang sudah dihapus
            ->latest()
            ->get();

        return view('mahasiswa.TugasAkhir.views.progresTA', compact('tugasAkhir'));
    }


    // Menampilkan form edit
    public function edit($id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Pastikan hanya mahasiswa yang sesuai yang dapat mengedit tugas akhir ini
        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswaId()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit tugas akhir ini.');
        }

        return view('mahasiswa.TugasAkhir.edit', compact('tugasAkhir'));
    }

    // Menyimpan perubahan tugas akhir
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Pastikan hanya mahasiswa yang sesuai yang dapat mengedit tugas akhir ini
        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswaId()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit tugas akhir ini.');
        }

        // Jika file proposal baru diunggah, simpan file baru
        if ($request->hasFile('file_proposal')) {
            // Hapus file lama jika ada
            if ($tugasAkhir->file_path) {
                Storage::disk('public')->delete($tugasAkhir->file_path);
            }

            // Simpan file proposal baru
            $file = $request->file('file_proposal');
            $fileName = time() . '_' . Str::slug($file->getClientOriginalName(), '_');
            $path = $file->storeAs('proposal_ta', $fileName, 'public');

            // Simpan path file baru ke tabel files
            $fileModel = File::create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => $this->assumedMahasiswaId(),
            ]);

            // Update file path di tabel tugas_akhir
            $tugasAkhir->file_path = $path;
        }

        // Update data tugas akhir
        $tugasAkhir->update([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
        ]);

        return redirect()->route('tugasAkhir.progress')->with('success', 'Tugas Akhir berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Pastikan hanya mahasiswa yang sesuai yang dapat membatalkan tugas akhir ini
        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswaId()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membatalkan tugas akhir ini.');
        }

        // Soft delete tugas akhir
        $tugasAkhir->delete();

        return redirect()->route('tugasAkhir.progress')->with('success', 'Tugas Akhir berhasil dibatalkan.');
    }

    public function cancel(Request $request, $id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Pastikan hanya mahasiswa yang sesuai yang dapat membatalkan tugas akhir ini
        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswaId()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membatalkan tugas akhir ini.');
        }

        // Simpan alasan pembatalan
        $alasan = $request->input('alasan');

        // Update status tugas akhir menjadi dibatalkan
        $tugasAkhir->update([
            'status' => 'dibatalkan',
            'alasan_pembatalan' => $alasan,
        ]);

        return redirect()->route('tugasAkhir.progress')->with('success', 'Tugas Akhir berhasil dibatalkan.');
    }

    public function showCancelled()
    {
        $tugasAkhirDibatalkan = TugasAkhir::where('mahasiswa_id', $this->assumedMahasiswaId())
            ->where('status', 'dibatalkan')
            ->latest()
            ->get();

        return view('mahasiswa.TugasAkhir.views.dibatalkan', compact('tugasAkhirDibatalkan'));
    }


}


