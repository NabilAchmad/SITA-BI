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
            'deskripsi' => 'required|string',
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
            'abstrak' => $request->deskripsi,
            'status' => 'diajukan',
            'tanggal_pengajuan' => now()->toDateString(),
            'file_path' => $path, 
        ]);

        return back()->with('success', 'Tugas Akhir berhasil diajukan!');
    }

    public function progress()
    {
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $this->assumedMahasiswaId())->latest()->get();

        return view('mahasiswa.TugasAkhir.views.progresTA', compact('tugasAkhir'));
    }
}

