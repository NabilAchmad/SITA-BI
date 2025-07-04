<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PenugasanService
{
    /**
     * Mengambil daftar mahasiswa yang sudah memiliki pembimbing lengkap.
     */
    public function getMahasiswaWithPembimbing(Request $request): LengthAwarePaginator
    {
        // ... (Tidak ada perubahan pada method ini)
        return TugasAkhir::query()
            ->with(['mahasiswa.user', 'peranDosenTA.dosen.user'])
            ->whereHas('peranDosenTA', fn($q) => $q->whereIn('peran', ['pembimbing1', 'pembimbing2']), '>=', 2)
            ->when($request->filled('prodi'), fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('prodi', $request->prodi)))
            ->when($request->filled('search'), fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('nim', 'like', "%{$request->search}%")->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$request->search}%"))))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Mengambil daftar mahasiswa yang membutuhkan penugasan pembimbing.
     */
    public function getMahasiswaNeedingPembimbing(Request $request): LengthAwarePaginator
    {
        // ... (Tidak ada perubahan pada method ini)
        return TugasAkhir::query()
            ->with(['mahasiswa.user', 'peranDosenTA.dosen.user'])
            ->where('status', TugasAkhir::STATUS_DISETUJUI)
            ->withCount(['peranDosenTA as pembimbing_count' => fn($q) => $q->whereIn('peran', ['pembimbing1', 'pembimbing2'])])
            ->having('pembimbing_count', '<', 2)
            ->when($request->filled('prodi'), fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('prodi', $request->prodi)))
            ->when($request->filled('search'), fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('nim', 'like', "%{$request->search}%")->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$request->search}%"))))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Menetapkan atau memperbarui pembimbing untuk sebuah Tugas Akhir.
     * Method ini sudah cukup fleksibel untuk menangani semua skenario.
     */
    public function assignOrUpdatePembimbing(TugasAkhir $tugasAkhir, array $validatedData): void
    {
        DB::transaction(function () use ($tugasAkhir, $validatedData) {
            $isFromTawaranTopik = !is_null($tugasAkhir->tawaran_topik_id);

            // Update pembimbing 1 HANYA jika BUKAN dari tawaran topik
            if (!$isFromTawaranTopik) {
                PeranDosenTa::updateOrCreate(
                    ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => PeranDosenTa::PERAN_PEMBIMBING_1],
                    ['dosen_id' => $validatedData['pembimbing1']]
                );
            }

            // Pembimbing 2 SELALU bisa di-update
            PeranDosenTa::updateOrCreate(
                ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => PeranDosenTa::PERAN_PEMBIMBING_2],
                ['dosen_id' => $validatedData['pembimbing2']]
            );
        });
    }
}
