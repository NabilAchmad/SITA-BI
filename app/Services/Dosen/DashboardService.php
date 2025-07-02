<?php

namespace App\Services\Dosen;

use App\Models\{BimbinganTA, Dosen, TugasAkhir, TawaranTopik, PeranDosenTA, JadwalSidang, Sidang};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardService
{
    protected $dosenId;

    public function __construct()
    {
        $this->dosenId = Auth::user()->dosen->id;
    }
    
    public function getRolePrioritas(): string
    {
        $dosen = Auth::user()->dosen;
        $roles = $dosen->user->roles->pluck('nama_role')->toArray();
        $prioritas = ['kajur', 'kaprodi', 'dosen', 'tamu'];

        return collect($prioritas)->first(fn($r) => in_array($r, $roles)) ?? 'dosen';
    }

    public function getPeranDosen(): array
    {
        return PeranDosenTA::where('dosen_id', Auth::user()->dosen->id)
            ->pluck('peran')
            ->toArray();
    }

    public function card1Data(): array
    {
        $role = $this->getRolePrioritas();

        return match ($role) {
            'kajur' => [
                'label' => 'Dosen Aktif',
                'icon' => 'fas fa-chalkboard-teacher',
                'value' => Dosen::count()
            ],
            'kaprodi' => [
                'label' => 'Pengajuan Tugas Akhir',
                'icon' => 'fas fa-file-upload',
                'value' => TugasAkhir::where('status', 'diajukan')->count()
            ],
            default => [
                'label' => 'Tawaran Topik Saya',
                'icon' => 'fas fa-book-reader',
                'value' => TawaranTopik::where('user_id', Auth::id())->count()
            ]
        };
    }

    public function card2Data(): array
    {
        return [
            'label' => 'Mahasiswa Aktif',
            'icon' => 'fas fa-user-graduate',
            'value' => TugasAkhir::whereNull('alasan_pembatalan')
                ->distinct('mahasiswa_id')
                ->count('mahasiswa_id')
        ];
    }

    public function card3Data(): array
    {
        return [
            'label' => 'Dosen Penguji',
            'icon' => 'fas fa-gavel',
            'value' => PeranDosenTA::whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
                ->distinct('dosen_id')->count('dosen_id')
        ];
    }

    public function card4Data(): array
    {
        return [
            'label' => 'Dosen Pembimbing',
            'icon' => 'fas fa-user-tie',
            'value' => PeranDosenTA::whereIn('peran', ['pembimbing1', 'pembimbing2'])
                ->distinct('dosen_id')->count('dosen_id')
        ];
    }

    public function jadwalBimbinganTerdekat($limit = 5)
    {
        return BimbinganTA::with(['tugasAkhir.mahasiswa.user'])
            ->where('dosen_id', $this->dosenId)
            ->where('tanggal_bimbingan', '>=', Carbon::now()->toDateString())
            ->whereIn('status_bimbingan', ['disetujui', 'selesai'])
            ->orderBy('tanggal_bimbingan', 'asc')
            ->limit($limit)
            ->get();
    }

    public function jadwalSidangTerdekat($limit = 5)
    {
        // Ambil TA yang dosen ini menjadi pengujinya
        $taPengujiIds = PeranDosenTA::where('dosen_id', $this->dosenId)
            ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
            ->pluck('tugas_akhir_id');

        $sidangIds = Sidang::whereIn('tugas_akhir_id', $taPengujiIds)->pluck('id');

        return JadwalSidang::with(['sidang.tugasAkhir.mahasiswa.user', 'ruangan'])
            ->whereIn('sidang_id', $sidangIds)
            ->where('tanggal', '>=', Carbon::now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->limit($limit)
            ->get();
    }
}
