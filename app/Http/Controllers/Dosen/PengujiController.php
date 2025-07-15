<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PendaftaranSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTA; // Import model PeranDosenTA
use App\Models\JadwalSidang; // Pastikan ini diimport jika digunakan untuk relasi jadwal
use App\Models\NilaiSidang;

class PengujiController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa yang diuji oleh dosen penguji yang sedang login.
     * Mengambil daftar sidang berdasarkan peran dosen penguji di tabel peran_dosen_ta.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil ID dosen yang sedang login
        $dosenId = Auth::user()->dosen->id;

        // 2. Cari semua ID Tugas Akhir di mana dosen ini perannya adalah 'penguji'
        $tugasAkhirIds = PeranDosenTA::where('dosen_id', $dosenId)
            ->where('peran', 'like', 'penguji%')
            ->pluck('tugas_akhir_id');

        // 3. Ambil data sidang berdasarkan daftar ID Tugas Akhir tersebut.
        $daftarSidang = Sidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->with([
                // Muat relasi ini untuk mendapatkan Judul, Nama, dan Peran Dosen
                'tugasAkhir.mahasiswa.user',
                'tugasAkhir.dosenPembimbing',
            ])
            ->latest()
            ->get();

        // 4. Kirim data ke view
        return view('dosen.penguji.index', compact('daftarSidang'));
    }

    /**
     * Tampilkan detail jadwal sidang untuk mahasiswa tertentu yang diuji.
     * Memverifikasi peran penguji melalui tabel peran_dosen_ta.
     *
     * @param  int  $id ID dari PendaftaranSidang
     * @return \Illuminate\View\View
     */
    public function show(Sidang $sidang) // Route-Model Binding otomatis menemukan sidang dari ID di URL
    {
        // 1. Ambil ID dosen yang sedang login
        $dosenId = Auth::user()->dosen->id;

        // 2. Verifikasi manual bahwa dosen yang login adalah penguji untuk sidang ini
        $isPenguji = PeranDosenTA::where('dosen_id', $dosenId)
            ->where('tugas_akhir_id', $sidang->tugas_akhir_id)
            ->where('peran', 'like', 'penguji%')
            ->exists();

        // 3. Jika bukan penguji, kembalikan ke halaman sebelumnya dengan pesan error
        if (!$isPenguji) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses sebagai penguji untuk sidang ini.');
        }

        // 4. Eager load relasi yang dibutuhkan oleh view untuk performa optimal
        $sidang->load([
            'jadwal.ruangan',
            'tugasAkhir.mahasiswa.user'
        ]);

        // 5. Kirim data ke view
        return view('dosen.penguji.show_jadwal', compact('sidang'));
    }

    /**
     * Tangani pengisian nilai sidang oleh dosen penguji.
     * Memverifikasi peran penguji melalui tabel peran_dosen_ta,
     * dan kemudian mengisi nilai ke kolom nilai_dosen_pengujiX di tabel sidang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id ID dari PendaftaranSidang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inputNilai(Request $request, Sidang $sidang)
    {

        // 2. Validasi: Pastikan data yang masuk sesuai format
        $validatedData = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // 3. Simpan atau Perbarui Nilai
        // Menggunakan updateOrCreate sangat direkomendasikan.
        // - Jika dosen belum pernah memberi nilai untuk sidang ini, data baru akan dibuat.
        // - Jika dosen sudah pernah memberi nilai dan ingin mengubahnya, data lama akan diperbarui.
        NilaiSidang::updateOrCreate(
            [
                // Kunci untuk mencari data yang sudah ada
                'sidang_id' => $sidang->id,
                'dosen_id'  => auth()->user()->dosen->id,
            ],
            [
                // Data yang akan disimpan atau diperbarui
                'skor'      => $validatedData['nilai'],
                'komentar'  => $validatedData['catatan'],
            ]
        );

        // 4. Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Nilai Berhasil Di Inputkan.'
        ]);
    }
}
