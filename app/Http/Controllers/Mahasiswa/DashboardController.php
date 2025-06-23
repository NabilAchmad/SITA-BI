<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::with('user')->where('user_id', $user->id)->first();

        // Ambil pengumuman untuk mahasiswa
        $pengumuman = \App\Models\Pengumuman::whereIn('audiens', ['mahasiswa', 'all_users'])
            ->orderBy('tanggal_dibuat', 'desc')
            ->limit(5)
            ->get();

        // Inisialisasi default
        $statusJudul = 'Belum Diajukan';
        $statusBimbingan = 'Belum Mulai';
        $statusSempro = 'Belum Dijadwalkan (Syarat belum terpenuhi)';
        $statusSidang = 'Menunggu Persyaratan';

        $namaMahasiswa = $mahasiswa?->user?->name ?? 'Mahasiswa';

        // Ambil data Tugas Akhir jika ada
        $ta = \App\Models\TugasAkhir::where('mahasiswa_id', $mahasiswa?->id)->first();

        if ($ta) {
            $statusJudul = ucfirst($ta->status);

            $jumlahBimbingan = \App\Models\BimbinganTa::where('tugas_akhir_id', $ta->id)
                ->where('status_bimbingan', 'disetujui')
                ->count();

            $statusBimbingan = $jumlahBimbingan > 0
                ? round(($jumlahBimbingan / 9) * 100) . '% Berjalan'
                : 'Belum Mulai';

            $seminarProposal = \App\Models\Sidang::where('tugas_akhir_id', $ta->id)
                ->where('jenis_sidang', 'proposal')
                ->first();

            $statusSempro = $seminarProposal
                ? 'Sudah Dijadwalkan'
                : 'Belum Dijadwalkan (Syarat belum terpenuhi)';

            $sidangAkhir = \App\Models\Sidang::where('tugas_akhir_id', $ta->id)
                ->where('jenis_sidang', 'akhir')
                ->first();

            $statusSidang = $sidangAkhir
                ? 'Sudah Dijadwalkan'
                : 'Menunggu Persyaratan';
        }

        return view('mahasiswa.views.dashboard', compact(
            'pengumuman',
            'statusJudul',
            'statusBimbingan',
            'statusSempro',
            'statusSidang',
            'namaMahasiswa'
        ));
    }
}
