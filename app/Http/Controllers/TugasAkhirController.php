<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TugasAkhir;
use App\Models\Mahasiswa;
use App\Models\BimbinganTA;
use App\Models\RevisiTa;
use App\Models\DokumenTa;
use App\Models\Sidang;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TugasAkhirController extends Controller
{
    private function assumedMahasiswaId()
    {
        // Ganti ini sesuai ID mahasiswa yang ada di tabel users
        return 1;
    }

    // Mengontrol akses mahasiswa ke dashboard tugas akhir
    public function dashboard()
    {
        $mahasiswaId = $this->assumedMahasiswaId();
        $mahasiswa = Mahasiswa::where('user_id', $mahasiswaId)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Akun ini tidak memiliki data mahasiswa.');
        }

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $sudahMengajukan = $tugasAkhir !== null;

        return view('mahasiswa.tugas-akhir.dashboard.dashboard', compact('tugasAkhir', 'sudahMengajukan', 'mahasiswa'));
    }

    // Menampilkan form ajukan Tugas Akhir
    public function ajukanForm()
    {
        $mahasiswaId = $this->assumedMahasiswaId();
        $mahasiswa = Mahasiswa::where('user_id', $mahasiswaId)->first();

        if (!$mahasiswa) {
            return redirect()->route('tugas-akhir.dashboard')->with('error', 'Akun ini tidak memiliki data mahasiswa.');
        }

        // Cek apakah mahasiswa sudah mengajukan tugas akhir
        $existing = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->exists();

        if ($existing) {
            return redirect()->route('tugas-akhir.dashboard')->withErrors(['error' => 'Anda sudah mengajukan tugas akhir.']);
        }

        return view('mahasiswa.tugas-akhir.crud-ta.create', compact('mahasiswa'));
    }

    // Menyimpan data Tugas Akhir
    public function store(Request $request)
    {
        $mahasiswaId = $this->assumedMahasiswaId();
        $mahasiswa = Mahasiswa::where('user_id', $mahasiswaId)->first();

        // Cek apakah mahasiswa sudah mengajukan tugas akhir
        $existing = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->exists();

        if ($existing) {
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
        // Gunakan auth()->id() jika login, atau ganti dengan simulasi user
        $simulasiUserId = 1;

        // Ambil data mahasiswa berdasarkan user_id
        $mahasiswa = Mahasiswa::where('user_id', $simulasiUserId)->first();

        if (!$mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        // Cek apakah mahasiswa sudah punya TA dengan status tertentu
        $isMengajukanTA = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['diajukan', 'revisi', 'disetujui', 'lulus_tanpa_revisi'])
            ->exists();

        // Ambil data tugas akhir terbaru dari mahasiswa
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->whereNull('deleted_at')
            ->latest()
            ->first();

        // Inisialisasi variabel kosong
        $progressBimbingan = collect();
        $revisi = collect();
        $dokumen = collect();
        $sidang = collect();

        if ($tugasAkhir) {
            $progressBimbingan = BimbinganTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get();

            $revisi = RevisiTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $dokumen = DokumenTa::where('tugas_akhir_id', $tugasAkhir->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $sidang = Sidang::where('tugas_akhir_id', $tugasAkhir->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Kirim ke view
        return view('mahasiswa.tugas-akhir.crud-ta.progress', compact(
            'tugasAkhir',
            'progressBimbingan',
            'revisi',
            'dokumen',
            'sidang',
            'isMengajukanTA'
        ));
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $tugasAkhir = TugasAkhir::findOrFail($id);

        // Pastikan hanya mahasiswa yang sesuai yang dapat mengedit tugas akhir ini
        if ($tugasAkhir->mahasiswa_id !== $this->assumedMahasiswaId()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit tugas akhir ini.');
        }

        return view('mahasiswa.tugas-akhir.edit', compact('tugasAkhir'));
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

        return view('mahasiswa.tugas-akhir.crud-ta.cancel', compact('tugasAkhirDibatalkan'));
    }
}
