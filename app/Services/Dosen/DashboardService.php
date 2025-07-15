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

        // Jika user tidak memiliki profil dosen, kembalikan data minimal untuk menghindari error.
        if (!$dosen) {
            return [
                'isPimpinan' => false,
                'isPembimbing' => false,
                'isPenguji' => false,
                'card1' => ['label' => 'Info', 'icon' => 'fas fa-info-circle', 'value' => 'Profil dosen tidak valid'],
                'card2' => [],
                'card3' => [],
                'card4' => [],
                'jadwalBimbingan' => collect(),
                'jadwalSidang' => collect(),
                'tawaranTopik' => collect(),
                'pengumumans' => Pengumuman::latest()->take(5)->get(),
                'riwayatTA' => collect(),
            ];
        }

        // ✅ PERBAIKAN: Panggil hasAnyRole() langsung pada objek $user.
        // Baris `$userRoles = $user->roles->pluck('name');` tidak diperlukan lagi untuk pengecekan ini.
        $isPimpinan = $user->hasAnyRole(['kajur', 'kaprodi-d3', 'kaprodi-d4']);

        // Query ini lebih efisien daripada memuat seluruh koleksi ke memori terlebih dahulu.
        $peranAkademik = PeranDosenTA::where('dosen_id', $dosen->id)->pluck('peran')->unique();
        $isPembimbing = $peranAkademik->contains(fn($p) => str_starts_with($p, 'pembimbing'));
        $isPenguji = $peranAkademik->contains(fn($p) => str_starts_with($p, 'penguji'));

        // Panggil metode-metode private untuk mengambil data spesifik
        return [
            'isPimpinan' => $isPimpinan,
            'isPembimbing' => $isPembimbing,
            'isPenguji' => $isPenguji,
            'card1' => $this->_getCard1Data($user), // $userRoles tidak perlu dikirim lagi
            'card2' => $this->_getCard2Data(),
            'card3' => $this->_getCard3Data(),
            'card4' => $this->_getCard4Data(),
            'jadwalBimbingan' => $isPembimbing ? $this->_getJadwalBimbinganTerdekat($dosen->id) : collect(),
            'jadwalSidang' => $isPenguji ? $this->_getJadwalSidangTerdekat($dosen->id) : collect(),
            'tawaranTopik' => $this->_getTawaranTopik($user->id),
            'pengumumans' => Pengumuman::latest()->take(5)->get(),
            'riwayatTA' => $isPimpinan ? TugasAkhir::latest()->take(10)->get() : collect(),
        ];
    }


    // --- Metode-metode private untuk mengambil data spesifik ---

    private function _getCard1Data(User $user): array
    {
        if ($user->hasRole('kajur')) {
            return ['label' => 'Dosen Aktif', 'icon' => 'fas fa-chalkboard-teacher', 'value' => Dosen::count()];
        }
        if ($user->hasAnyRole(['kaprodi-d3', 'kaprodi-d4'])) {
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

    /**
     * Mengambil jadwal sidang terdekat di mana dosen adalah penguji.
     */
    private function _getJadwalSidangTerdekat(int $dosenId, int $limit = 5)
    {
        // Langkah 1: Ambil semua ID tugas akhir di mana dosen ini adalah penguji.
        $tugasAkhirIds = PeranDosenTA::where('dosen_id', $dosenId)
            ->where('peran', 'like', 'penguji%')
            ->pluck('tugas_akhir_id');

        // Jika tidak ada, kembalikan koleksi kosong untuk efisiensi.
        if ($tugasAkhirIds->isEmpty()) {
            return collect();
        }

        // Langkah 2: Cari Sidang berdasarkan daftar ID tersebut yang jadwalnya akan datang.
        return Sidang::with(['tugasAkhir.mahasiswa.user', 'jadwal.ruangan'])
            // ✅ Gunakan whereIn yang efisien, bukan whereHas pada accessor
            ->whereIn('tugas_akhir_id', $tugasAkhirIds)
            // Filter hanya untuk jadwal yang akan datang atau hari ini
            ->whereHas('jadwal', function ($query) {
                $query->where('tanggal', '>=', now()->startOfDay());
            })
            ->orderBy(
                // Mengurutkan berdasarkan tanggal dari tabel relasi 'jadwal'
                \App\Models\JadwalSidang::select('tanggal')
                    ->whereColumn('sidang_id', 'sidang.id')
                    ->orderBy('tanggal', 'asc')
                    ->limit(1)
            )
            ->limit($limit)
            ->get();
    }

    private function _getTawaranTopik(int $userId)
    {
        return TawaranTopik::with(['tugasAkhir.mahasiswa.user'])
            ->where('user_id', $userId)->get();
    }
}
