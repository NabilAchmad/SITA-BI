<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\File;
use App\Models\TugasAkhir;
use App\Models\PeranDosenTA;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PendaftaranSidangController extends Controller
{
    public function form()
    {
        $userId = 18;

        $mahasiswa = Mahasiswa::with(['tugasAkhir.peranDosen.dosen', 'tugasAkhir'])->where('user_id', $userId)->first();

        return view('mahasiswa.Sidang.views.form', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_ta' => 'required|string',
            'dosen_pembimbing_1' => 'required|exists:dosen,id',
            'dosen_pembimbing_2' => 'required|exists:dosen,id|different:dosen_pembimbing_1',
            'jumlah_bimbingan' => 'required|integer|min:0',
            'file_ta' => 'required|file|mimes:pdf,doc,docx'
        ]);

        $userId = 35;
        $mahasiswa = Mahasiswa::where('user_id', $userId)->firstOrFail();

        // Upload file
        $filePath = $request->file('file_ta')->store('tugas_akhir', 'public');

        // Simpan file ke tabel files
        $file = File::create([
            'file_path' => $filePath,
            'file_type' => $request->file('file_ta')->getClientMimeType(),
            'uploaded_by' => $userId
        ]);

        // Simpan data tugas akhir
        $ta = TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => $request->judul_ta,
            'status' => 'diajukan',
            'tanggal_pengajuan' => Carbon::now(),
            'file_path' => $filePath
        ]);

        // Simpan dosen pembimbing
        PeranDosenTA::insert([
            [
                'dosen_id' => $request->dosen_pembimbing_1,
                'tugas_akhir_id' => $ta->id,
                'peran' => 'pembimbing1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'dosen_id' => $request->dosen_pembimbing_2,
                'tugas_akhir_id' => $ta->id,
                'peran' => 'pembimbing2',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return back()->with('success', 'Pendaftaran sidang berhasil.');
    }
}
