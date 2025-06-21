<?php

namespace App\Http\Controllers\Dosen;

use Illuminate\Http\Request;
use App\Models\RevisiTA;
use App\Http\Controllers\Controller;

class TugasAkhirController extends Controller
{
    // Menampilkan dashboard mahasiswa (file TA, revisi, dan list mahasiswa sidang)
    public function dashboard()
    {
        $fileTA = (object)[
            'file_path' => 'tugas_akhir/contoh_file.pdf'
        ];

        $revisi = RevisiTA::orderByDesc('created_at')->get();

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
                        'status_revisi' => 'Menunggu ACC',
                        'file_pdf' => 'tugas_akhir/123456789.pdf'
                    ],
                    (object)[
                        'id' => 102,
                        'created_at' => now()->subDays(7),
                        'catatan' => 'Proposal telah direvisi.',
                        'status_revisi' => 'ACC',
                        'file_pdf' => 'tugas_akhir/123456789_proposal.pdf'
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
            'tugas_akhir_id' => 'required|exists:tugas_akhir,id',
        ]);

        // Buat revisi baru, pastikan tugas_akhir_id diisi!
        RevisiTA::create([
            'catatan' => $request->komentar_revisi,
            'status_revisi' => 'Menunggu ACC',
            'tugas_akhir_id' => $request->tugas_akhir_id,
        ]);

        return redirect()->route('ta.dashboard')->with('success', 'Komentar revisi berhasil dikirim.');
    }

    // Menyetujui (ACC) revisi tertentu
    public function acc($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status_revisi = 'ACC';
        $revisi->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'Revisi telah di-ACC.']);
        }

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah di-ACC.');
    }

    // Menolak revisi tertentu
    public function tolak($id)
    {
        $revisi = RevisiTA::findOrFail($id);
        $revisi->status_revisi = 'Ditolak';
        $revisi->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'Revisi telah ditolak.']);
        }

        return redirect()->route('ta.dashboard')->with('success', 'Revisi telah ditolak.');
    }
}