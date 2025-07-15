<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranSidang;
use App\Services\Dosen\DosenSidangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenSidangController extends Controller
{
    protected DosenSidangService $sidangService;

    public function __construct(DosenSidangService $sidangService)
    {
        $this->sidangService = $sidangService;
    }

    /**
     * Menampilkan daftar pendaftaran sidang yang perlu diverifikasi oleh dosen.
     */
    public function index()
    {
        // Dapatkan ID dosen yang sedang login. Gunakan optional chaining (?) untuk keamanan.
        $dosenId = Auth::user()?->dosen?->id;

        // Jika user bukan dosen atau tidak punya relasi, kembalikan daftar kosong untuk mencegah error.
        if (!$dosenId) {
            return view('dosen.sidang.approvals.index', ['pendaftaranList' => []]);
        }

        // --- QUERY BARU YANG SUDAH DIPERBAIKI ---
        // Logika:
        // Ambil pendaftaran sidang DI MANA:
        // (status pembimbing 1 adalah 'menunggu' DAN dosen yang login adalah 'pembimbing1' untuk TA tersebut)
        // ATAU
        // (status pembimbing 2 adalah 'menunggu' DAN dosen yang login adalah 'pembimbing2' untuk TA tersebut)
        $pendaftaranList = PendaftaranSidang::query()
            ->where(function ($query) use ($dosenId) {
                // Kondisi untuk Pembimbing 1
                $query->where('status_pembimbing_1', 'menunggu')
                    ->whereHas('tugasAkhir.peranDosenTa', function ($subQuery) use ($dosenId) {
                        $subQuery->where('dosen_id', $dosenId)
                            ->where('peran', 'pembimbing1');
                    });
            })
            ->orWhere(function ($query) use ($dosenId) {
                // Kondisi untuk Pembimbing 2
                $query->where('status_pembimbing_2', 'menunggu')
                    ->whereHas('tugasAkhir.peranDosenTa', function ($subQuery) use ($dosenId) {
                        $subQuery->where('dosen_id', $dosenId)
                            ->where('peran', 'pembimbing2');
                    });
            })
            ->with('tugasAkhir.mahasiswa.user') // Eager load untuk performa
            ->latest() // Urutkan dari yang paling baru
            ->get();

        // Kirim data yang sudah benar ke view. Nama view diperbarui sesuai permintaan.
        return view('dosen.sidang.approvals.index', compact('pendaftaranList'));
    }

    /**
     * Menampilkan detail pendaftaran sidang untuk diverifikasi.
     */
    public function show(PendaftaranSidang $pendaftaran)
    {
        // Otorisasi bisa ditambahkan di sini jika perlu
        $pendaftaran->load('tugasAkhir.mahasiswa.user', 'files');
        return view('dosen.sidang.approvals.index', compact('pendaftaran'));
    }
}
