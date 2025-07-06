<?php

namespace App\Services\Mahasiswa;

use App\Http\Requests\Mahasiswa\StoreSeminarProposalRequest;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\DB;
use Exception;

class SeminarProposalService
{
    /**
     * Logika untuk membuat pendaftaran seminar proposal baru.
     *
     * @param StoreSeminarProposalRequest $request
     * @param Mahasiswa $mahasiswa
     * @return TugasAkhir
     * @throws \Exception
     */
    public function createProposal(StoreSeminarProposalRequest $request, Mahasiswa $mahasiswa): TugasAkhir
    {
        // Pindahkan logika pengecekan dari controller ke service.
        $proposalAktif = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotIn('status', ['lulus_dengan_revisi', 'lulus_tanpa_revisi', 'dibatalkan', 'ditolak'])
            ->exists();

        if ($proposalAktif) {
            // Lemparkan exception untuk ditangani di controller.
            throw new Exception('Anda sudah memiliki proposal yang sedang dalam proses.');
        }

        // Gunakan DB Transaction untuk memastikan integritas data.
        // Jika ada lebih dari satu operasi database, ini sangat penting.
        return DB::transaction(function () use ($request, $mahasiswa) {
            return TugasAkhir::create([
                'mahasiswa_id'    => $mahasiswa->id,
                'judul'           => $request->judul_proposal,
                'status'          => 'draft',
                'tanggal_pengajuan' => now(),
            ]);
        });
    }
}
