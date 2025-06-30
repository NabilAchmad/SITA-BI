<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\Log;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\PeranDosenTa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Mengambil semua data yang dibutuhkan untuk dashboard admin.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        // PERBAIKAN: Mengubah kunci cache untuk memaksa Laravel membuat cache baru.
        // Ini akan menyelesaikan error 'Undefined variable'.
        return Cache::remember('admin_dashboard_data_v2', now()->addMinutes(5), function () {

            $dosenAktif = Dosen::with('user')->get()->filter(fn($d) => $d->isOnline());

            return [
                'totalDosen' => Dosen::count(),
                'totalMahasiswa' => Mahasiswa::count(),
                'totalPengumuman' => Pengumuman::count(),
                'riwayatTA' => TugasAkhir::with('mahasiswa.user')->latest()->take(10)->get(),
                'pengumumans' => Pengumuman::with('pembuat')->latest()->take(5)->get(),
                'logs' => Log::with('user')->latest()->take(10)->get(),
                'totalPembimbing' => PeranDosenTa::whereIn('peran', ['pembimbing1', 'pembimbing2'])->distinct('dosen_id')->count(),
                'totalPenguji' => PeranDosenTa::whereIn('peran', ['penguji1', 'penguji2', 'penguji3'])->distinct('dosen_id')->count(),
                'mahasiswaAktif' => TugasAkhir::where('status', '!=', 'dibatalkan')->distinct('mahasiswa_id')->count(),
                'dosenAktif' => $dosenAktif,
            ];
        });
    }
}
