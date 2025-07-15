<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Services\Dosen\PersetujuanSidangService; // <-- Import service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class SidangApprovalController extends Controller
{
    protected $sidangService;

    // Gunakan constructor untuk inject service, ini adalah praktik yang baik
    public function __construct(PersetujuanSidangService $sidangService)
    {
        $this->sidangService = $sidangService;
    }

    /**
     * Menyetujui pendaftaran sidang.
     */
    public function approve($pendaftaranId)
    {
        try {
            $this->sidangService->handleApproval($pendaftaranId, Auth::user());
        } catch (AuthorizationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Sebaiknya catat error ini ke log untuk debugging
            // Log::error('Gagal menyetujui sidang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada sistem, silakan coba lagi.');
        }

        // return redirect()->route('dosen.verifikasi-sidang.index')
        //     ->with('success', 'Persetujuan sidang berhasil direkam.');

        return redirect()->route('dosen.verifikasi-sidang.index')->with('alert', [
            'type' => 'error',
            'title' => 'Pengajuan Diterima',
            'message' => 'Pengajuan sidang berhasil diterima.'
        ]);
    }

    /**
     * Menolak pendaftaran sidang.
     * Perhatikan: Parameter diubah dari $sidangId menjadi $pendaftaranId agar konsisten.
     */
    public function reject(Request $request, $pendaftaranId)
    {
        // Validasi input untuk catatan penolakan
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        try {
            $this->sidangService->handleRejection($pendaftaranId, Auth::user(), $request->input('catatan'));
        } catch (AuthorizationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Log::error('Gagal menolak sidang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada sistem, silakan coba lagi.');
        }

        // Redirect ke route yang sesuai
        return redirect()->route('dosen.verifikasi-sidang.index')
            ->with('success', 'Pendaftaran sidang berhasil ditolak.');
    }
}
