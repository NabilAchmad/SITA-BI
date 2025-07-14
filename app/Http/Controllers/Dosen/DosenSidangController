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
        $dosenId = Auth::user()->dosen->id;

        $pendaftaranList = PendaftaranSidang::where(function ($query) use ($dosenId) {
            $query->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('pembimbing_1_id', $dosenId);
            })->where('status_pembimbing_1', 'menunggu');
        })->orWhere(function ($query) use ($dosenId) {
            $query->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('pembimbing_2_id', $dosenId);
            })->where('status_pembimbing_2', 'menunggu');
        })
        ->with('tugasAkhir.mahasiswa.user') // Eager loading untuk efisiensi
        ->latest()
        ->get();

        return view('dosen.sidang.index', compact('pendaftaranList'));
    }

    /**
     * Menampilkan detail pendaftaran sidang untuk diverifikasi.
     */
    public function show(PendaftaranSidang $pendaftaran)
    {
        // Otorisasi bisa ditambahkan di sini jika perlu
        $pendaftaran->load('tugasAkhir.mahasiswa.user');
        return view('dosen.sidang.show', compact('pendaftaran'));
    }

    /**
     * Memproses form keputusan (Setuju/Tolak) dari dosen.
     */
    public function prosesVerifikasi(Request $request, PendaftaranSidang $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:5000',
        ]);

        try {
            $dosen = Auth::user()->dosen;
            $this->sidangService->prosesKeputusanDosen(
                $pendaftaran,
                $dosen,
                $request->input('status'),
                $request->input('catatan')
            );
            
            return redirect()->route('dosen.sidang.index')->with('success', 'Keputusan verifikasi berhasil disimpan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
