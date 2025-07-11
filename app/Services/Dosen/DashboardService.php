<?php

// --- File: app/Services/Dosen/DashboardService.php ---
// ✅ PERBAIKAN: Service sekarang menjadi pusat semua logika.

namespace App\Services\Dosen;

use App\Models\{BimbinganTA, Dosen, Pengumuman, TugasAkhir, TawaranTopik, PeranDosenTA, Sidang, User};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Metode utama untuk mengambil semua data yang dibutuhkan oleh dashboard dosen.
     * @param User $user Pengguna yang sedang login.
     * @return array Data yang siap dikirim ke view.
     */
    public function getDataForDashboard(User $user): array
    {
        $dosen = $user->dosen;
        $userRoles = $user->roles->pluck('name');
        $peranAkademik = $dosen ? $dosen->peranDosenTa->pluck('peran')->unique() : collect();

        // Siapkan "bendera" hak akses
        $isPimpinan = $userRoles->hasAny(['kajur', 'kaprodi-d3', 'kaprodi-d4']);
        $isPembimbing = $peranAkademik->contains(fn($p) => str_starts_with($p, 'pembimbing'));
        $isPenguji = $peranAkademik->contains(fn($p) => str_starts_with($p, 'penguji'));

        // Panggil metode-metode private untuk mengambil data spesifik
        return [
            'isPimpinan' => $isPimpinan,
            'isPembimbing' => $isPembimbing,
            'isPenguji' => $isPenguji,
            'card1' => $this->_getCard1Data($user, $userRoles),
            'card2' => $this->_getCard2Data(),
            'card3' => $this->_getCard3Data(),
            'card4' => $this->_getCard4Data(),
            'jadwalBimbingan' => $isPembimbing ? $this->_getJadwalBimbinganTerdekat($dosen->id) : collect(),
            'jadwalSidang' => $isPenguji ? $this->_getJadwalSidangTerdekat($dosen->id) : collect(),
            'tawaranTopik' => $this->_getTawaranTopik($user->id),
            'pengumumans' => Pengumuman::latest()->take(5)->get(), // Ambil 5 terbaru
            'riwayatTA' => $isPimpinan ? TugasAkhir::latest()->take(10)->get() : collect(), // Hanya untuk pimpinan
        ];
    }

    // --- Metode-metode private untuk mengambil data spesifik ---

    private function _getCard1Data(User $user, $userRoles): array
    {
        if ($userRoles->contains('kajur')) {
            return ['label' => 'Dosen Aktif', 'icon' => 'fas fa-chalkboard-teacher', 'value' => Dosen::count()];
        }
        if ($userRoles->hasAny(['kaprodi-d3', 'kaprodi-d4'])) {
            return ['label' => 'Pengajuan Tugas Akhir', 'icon' => 'fas fa-file-upload', 'value' => TugasAkhir::where('status', 'diajukan')->count()];
        }
        return ['label' => 'Tawaran Topik Saya', 'icon' => 'fas fa-book-reader', 'value' => TawaranTopik::where('user_id', $user->id)->count()];
    }

    private function _getCard2Data(): array
    {
        return ['label' => 'Mahasiswa Aktif', 'icon' => 'fas fa-user-graduate', 'value' => TugasAkhir::active()->distinct('mahasiswa_id')->count('mahasiswa_id')];
    }

    private function _getCard3Data(): array
    {
        return ['label' => 'Dosen Penguji', 'icon' => 'fas fa-gavel', 'value' => PeranDosenTA::where('peran', 'like', 'penguji%')->distinct('dosen_id')->count('dosen_id')];
    }

    private function _getCard4Data(): array
    {
        return ['label' => 'Dosen Pembimbing', 'icon' => 'fas fa-user-tie', 'value' => PeranDosenTA::where('peran', 'like', 'pembimbing%')->distinct('dosen_id')->count('dosen_id')];
    }

    private function _getJadwalBimbinganTerdekat(int $dosenId, int $limit = 5)
    {
        return BimbinganTA::with(['tugasAkhir.mahasiswa.user'])
            ->where('dosen_id', $dosenId)
            ->where('tanggal_bimbingan', '>=', now())
            ->whereIn('status_bimbingan', ['disetujui', 'selesai'])
            ->orderBy('tanggal_bimbingan', 'asc')
            ->limit($limit)
            ->get();
    }

    private function _getJadwalSidangTerdekat(int $dosenId, int $limit = 5)
    {
        return Sidang::with(['tugasAkhir.mahasiswa.user', 'jadwal.ruangan'])
            ->whereHas('peranDosenTa', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId)->where('peran', 'like', 'penguji%');
            })
            ->whereHas('jadwal', function ($query) {
                $query->where('tanggal', '>=', now());
            })
            ->limit($limit)
            ->get();
    }

    private function _getTawaranTopik(int $userId)
    {
        return TawaranTopik::with(['tugasAkhir.mahasiswa.user'])
            ->where('user_id', $userId)->get();
    }
}
