<?php

namespace App\Services\Dosen;

use App\Models\BimbinganTA;
use App\Models\CatatanBimbingan;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BimbinganService
{
    protected ?Dosen $dosen;

    public function __construct()
    {
        if (Auth::check() && Auth::user()->hasRole('dosen')) {
            $this->dosen = Auth::user()->dosen;
        } else {
            $this->dosen = null;
        }
    }

    public function getPengajuanBimbingan(Request $request)
    {
        if (!$this->dosen) {
            return collect();
        }

        $dosenId = $this->dosen->id;
        $topikIds = $this->dosen->topik()->pluck('id');

        $pengajuanDariTopikDosen = TugasAkhir::whereIn('tawaran_topik_id', $topikIds)
            ->where('status', 'disetujui')
            ->with('mahasiswa.user', 'tawaranTopik')
            ->get();

        $pengajuanDariMahasiswa = TugasAkhir::whereNull('tawaran_topik_id')
            ->where('status', 'disetujui')
            ->whereHas('dosenPembimbing', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->with('mahasiswa.user', 'dosenPembimbing.user')
            ->get();

        $semuaPengajuan = $pengajuanDariTopikDosen->merge($pengajuanDariMahasiswa);

        return $semuaPengajuan->unique('id')->values();
    }

    public function getAllBimbinganAktif(Request $request)
    {
        $query = TugasAkhir::query()
            ->active()
            // ✅ PERBAIKAN: Eager load relasi dasar 'dosenPembimbing' dan relasi 'user' di dalamnya.
            // Accessor akan bekerja secara efisien dari data yang sudah dimuat ini.
            ->with([
                'mahasiswa.user',
                'dosenPembimbing.user',
            ]);

        $query->when($request->input('search'), function ($q, $search) {
            $q->where(function ($subq) use ($search) {
                $subq->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($mhsQuery) use ($search) {
                        $mhsQuery->where('nim', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        });

        $query->when($request->input('prodi'), function ($q, $prodi) {
            $q->whereHas('mahasiswa', function ($mhsQuery) use ($prodi) {
                $mhsQuery->where('program_studi', $prodi);
            });
        });

        return $query->latest()->get();
    }

    public function getDataForBimbinganDetailPage(TugasAkhir $tugasAkhir): array
    {
        $loggedInDosenId = $this->dosen?->id;

        // ✅ PERBAIKAN: Eager load relasi dasar yang benar.
        $tugasAkhir->load([
            'dosenPembimbing.user',
            'mahasiswa.user',
            'bimbinganTa',
            'dokumenTa'
        ]);

        $sesiAktif = $tugasAkhir->bimbinganTa
            ->whereIn('status_bimbingan', ['diajukan','disetujui', 'dijadwalkan']);

        $allCatatan = CatatanBimbingan::whereIn('bimbingan_ta_id', $tugasAkhir->bimbinganTa->pluck('id'))
            ->with('author.user')
            ->orderBy('created_at', 'asc')
            ->get();

        $catatanList = $allCatatan->filter(function ($catatan) use ($loggedInDosenId) {
            if ($catatan->author_type === 'App\Models\Mahasiswa') return true;
            if (is_null($loggedInDosenId)) return true;
            if ($catatan->author_type === 'App\Models\Dosen' && $catatan->author_id === $loggedInDosenId) return true;
            return false;
        })->values();

        $riwayatDokumen = $tugasAkhir->dokumenTa->sortBy('created_at');
        $dokumenTerbaru = $riwayatDokumen->last();

        // Tidak ada perubahan di sini, accessor dipanggil secara otomatis
        // dan menggunakan data dari 'dosenPembimbing' yang sudah di-load.
        $pembimbing1 = $tugasAkhir->pembimbingSatu;
        $pembimbing2 = $tugasAkhir->pembimbing_dua;

        $bimbinganCountP1 = $pembimbing1 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;
        $bimbinganCountP2 = $pembimbing2 ? $tugasAkhir->bimbinganTa()->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;

        return [
            'tugasAkhir'       => $tugasAkhir,
            'sesiAktif'        => $sesiAktif,
            'catatanList'      => $catatanList,
            'riwayatDokumen'   => $riwayatDokumen,
            'dokumenTerbaru'   => $dokumenTerbaru,
            'pembimbing1'      => $pembimbing1,
            'pembimbing2'      => $pembimbing2,
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
        ];
    }

    // --- Sisa metode (setJadwal, createCatatan, dll) tidak perlu diubah ---
    // Logika di dalamnya sudah benar dan akan berfungsi dengan model yang baru.

    public function setJadwal(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        $pengajuanBimbingan = $tugasAkhir->bimbinganTa()
            ->where('status_bimbingan', 'disetujui')
            ->where('dosen_id', $this->dosen->id)
            ->firstOrFail();

        $pengajuanBimbingan->update([
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'jam_bimbingan'     => $data['jam_bimbingan'],
            'status_bimbingan'  => 'dijadwalkan',
        ]);
    }

    public function createCatatan(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        $sesiBimbinganAktif = $tugasAkhir->bimbinganTa()
            ->whereIn('status_bimbingan', ['disetujui', 'dijadwalkan'])
            ->get();

        if ($sesiBimbinganAktif->isEmpty()) {
            throw new \Exception('Tidak ada sesi bimbingan aktif untuk menambahkan catatan.');
        }

        foreach ($sesiBimbinganAktif as $sesi) {
            $sesi->catatan()->create([
                'catatan'     => $data['catatan'],
                'author_type' => Dosen::class,
                'author_id'   => $this->dosen->id,
            ]);
        }
    }

    public function selesaikanSesi(BimbinganTA $bimbingan): void
    {
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);

        if ($bimbingan->dosen_id !== $this->dosen->id) {
            throw new UnauthorizedException('Anda tidak berwenang menyelesaikan sesi bimbingan ini.');
        }
        if ($bimbingan->status_bimbingan !== 'dijadwalkan') {
            throw new \Exception('Hanya bimbingan yang berstatus "dijadwalkan" yang bisa diselesaikan.');
        }

        DB::transaction(function () use ($bimbingan) {
            $bimbingan->update(['status_bimbingan' => 'selesai']);
            $tugasAkhir = $bimbingan->tugasAkhir;
            $dokumenTerkait = $tugasAkhir->dokumenTa()->latest('version')->first();

            if (!$dokumenTerkait) {
                Log::warning('Proses dihentikan: Tidak ditemukan dokumen TA untuk Tugas Akhir ID: ' . $tugasAkhir->id);
                return;
            }

            $peranDosen = $tugasAkhir->dosenPembimbing()->where('dosen_id', $this->dosen->id)->first();

            if ($peranDosen) {
                $peran = $peranDosen->pivot->peran;
                $updateData = [];

                if ($peran === 'pembimbing1') {
                    $updateData['divalidasi_oleh_p1'] = $this->dosen->id;
                } elseif ($peran === 'pembimbing2') {
                    $updateData['divalidasi_oleh_p2'] = $this->dosen->id;
                }

                if (!empty($updateData)) {
                    $dokumenTerkait->update($updateData);
                    $dokumenTerkait->refresh();
                    if ($dokumenTerkait->divalidasi_oleh_p1 && $dokumenTerkait->divalidasi_oleh_p2) {
                        $dokumenTerkait->update(['status_validasi' => 'disetujui']);
                    }
                }
            } else {
                Log::warning("Dosen ID {$this->dosen->id} mencoba menyelesaikan sesi untuk TA ID {$tugasAkhir->id} namun perannya tidak ditemukan.");
            }
        });
    }

    public function cancelBimbingan(BimbinganTA $bimbingan): void
    {
        $this->authorizeDosenIsPembimbing($bimbingan->tugasAkhir);

        $sesiTerkait = $bimbingan->tugasAkhir->bimbinganTa()
            ->where('sesi_ke', $bimbingan->sesi_ke)
            ->where('status_bimbingan', 'dijadwalkan');

        if ($sesiTerkait->count() === 0) {
            throw new \Exception('Tidak ada jadwal aktif untuk dibatalkan.');
        }
        $sesiTerkait->update(['status_bimbingan' => 'dibatalkan']);
    }

    private function authorizeDosenIsPembimbing(TugasAkhir $tugasAkhir): void
    {
        $user = auth()->user();

        // ✅ Izinkan jika user punya permission pantau semua
        if ($user->can('pantau-semua-bimbingan')) {
            return;
        }

        // ✅ Izinkan jika policy `view` mengizinkan
        if ($user->can('view', $tugasAkhir)) {
            return;
        }

        // ✅ Izinkan jika user adalah pembimbing (fallback jika belum lolos di atas)
        if (
            $this->dosen &&
            $tugasAkhir->dosenPembimbing()->where('dosen_id', $this->dosen->id)->exists()
        ) {
            return;
        }

        // ❌ Kalau semua gagal, lempar unauthorized
        throw new UnauthorizedException('Anda tidak diizinkan mengakses tugas akhir ini.');
    }
}