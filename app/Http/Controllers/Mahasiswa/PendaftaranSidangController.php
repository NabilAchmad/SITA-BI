<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreSeminarProposalRequest; // Gunakan Form Request yang baru
use App\Models\Mahasiswa;
use App\Services\Mahasiswa\SeminarProposalService; // Gunakan Service yang baru
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk mencatat error

class PendaftaranSidangController extends Controller
{
    // Inject service melalui constructor agar bisa digunakan di semua method.
    protected $seminarProposalService;

    public function __construct(SeminarProposalService $seminarProposalService)
    {
        $this->seminarProposalService = $seminarProposalService;
    }

    /**
     * Menampilkan halaman form pendaftaran seminar proposal.
     */
    public function form()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        // Nama view bisa disesuaikan, 'create' lebih mengikuti konvensi RESTful
        return view('mahasiswa.sidang.views.sempro', compact('mahasiswa'));
    }

    /**
     * Menyimpan data pendaftaran seminar proposal.
     */
    public function store(StoreSeminarProposalRequest $request)
    {
        try {
            $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

            // Panggil service untuk menjalankan logika bisnis.
            $this->seminarProposalService->createProposal($request, $mahasiswa);

            return redirect()->route('tugas-akhir.progress')
                ->with('alert', [
                    'title'   => 'Berhasil Disimpan!',
                    'message' => 'Draft proposal Anda telah berhasil disimpan. Silakan lanjutkan ke tahap berikutnya.',
                    'type'    => 'success',
                ]);
        } catch (\Exception $e) {
            // Catat error ke log untuk debugging.
            Log::error('Gagal menyimpan proposal: ' . $e->getMessage());

            // Kembalikan ke halaman sebelumnya dengan pesan error.
            return redirect()->back()
                ->with('alert', [
                    'title'   => 'Gagal!',
                    'message' => $e->getMessage(), // Tampilkan pesan error dari service
                    'type'    => 'error',
                ])
                ->withInput(); // Kembalikan input sebelumnya ke form
        }
    }
}
