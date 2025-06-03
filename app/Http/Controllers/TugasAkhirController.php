<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevisiTA;

class TugasAkhirController extends Controller
{
    // Menampilkan dashboard mahasiswa (file TA, revisi, dan list mahasiswa sidang)
    public function dashboard()
    {
        // Ambil file tugas akhir, contoh dummy (ganti dengan query sesuai kebutuhan)
        $fileTA = (object)[
            'file_path' => 'tugas_akhir/contoh_file.pdf'
        ];

        // Ambil data revisi dari database
        $revisi = RevisiTA::orderByDesc('created_at')->get();

        // Data mahasiswa sidang (dummy, ganti dengan query sesuai kebutuhan)
        $mahasiswaSidang = collect([
            (object)[
                'id' => 1,
                'nama' => 'Ahmad Fauzi',
                'nim' => '123456789',
                'judul_ta' => 'Sistem Informasi Akademik',
                'prodi' => 'Sistem Informasi',
                'dosen_pembimbing' => 'Dr. Budi Santoso',
                'kemajuan' => [
                    (object)[
                        'id' => 101,
                        'created_at' => now(),
                        'catatan' => 'Bab 1 selesai',
                        'status_revisi' => 'Menunggu ACC'
                    ],
                    (object)[
                        'id' => 102,
                        'created_at' => now()->subDays(7),
                        'catatan' => 'Proposal telah direvisi.',
                        'status_revisi' => 'ACC'
                    ]
                ]
            ],
            // Tambahkan data mahasiswa lain jika perlu
        ]);

        return view('admin.ta.dashboard.dashboard', compact('revisi', 'fileTA', 'mahasiswaSidang'));
    }

    // Menyimpan komentar revisi dari modal
    public function revisiStore(Request $request)
    {
        $request->validate([
            'komentar_revisi' => 'required|string|max:1000',
        ]);

        // Buat revisi baru
        RevisiTA::create([
            // 'tugas_akhir_id' => ... // isi jika perlu relasi
            // 'user_id' => auth()->id(), // isi jika perlu
            'catatan' => $request->komentar_revisi,
            'status_revisi' => 'Menunggu ACC',
        ]);

        return redirect()->route('ta.dashboard')->with('success', 'Komentar revisi berhasil dikirim.');
    }

    // Menyetujui (ACC) revisi tertentu
    public function acc($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status_revisi = 'ACC';
        $revisi->save();

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah di-ACC.');
    }

    // Menolak revisi tertentu
    public function tolak($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status_revisi = 'Ditolak';
        $revisi->save();

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah ditolak.');
    }
}