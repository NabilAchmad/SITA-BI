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
use App\Models\PendaftaranSidang;

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
        // Cek dulu apakah pengguna yang login adalah dosen
        if (!$this->dosen) {
            return collect(); // Kembalikan koleksi kosong jika bukan dosen
        }

        // =================================================================
        // LOGIKA UNTUK MODE PANTAU SEMUA
        // =================================================================
        // Cek apakah mode=pantau_semua dan user punya hak akses
        if ($request->input('mode') == 'pantau_semua' && auth()->user()->can('pantau-semua-bimbingan')) {

            // 1. Dapatkan ID dosen yang sedang login.
            // Tanda tanya (?->) digunakan untuk keamanan jika user bukan seorang dosen.
            $loggedInDosenId = auth()->user()->dosen?->id;

            // Ambil SEMUA tugas akhir yang statusnya sudah disetujui...
            $query = TugasAkhir::where('status', 'disetujui');

            // 2. Tambahkan filter HANYA JIKA user tersebut adalah dosen.
            if ($loggedInDosenId) {
                // ...KECUALI tugas akhir yang memiliki relasi 'dosenPembimbing'
                // di mana ID dosennya adalah ID dosen yang login.
                $query->whereDoesntHave('dosenPembimbing', function ($q) use ($loggedInDosenId) {
                    $q->where('dosen.id', $loggedInDosenId);
                });
            }

            // Kembalikan hasilnya beserta relasi yang dibutuhkan oleh view.
            return $query->with([
                'mahasiswa.user',
                'dosenPembimbing.user' // Untuk menampilkan nama P1 di view
            ])
                ->get();
        } else {
            $dosenId = $this->dosen->id;
            $topikIds = $this->dosen->topik()->pluck('id');

            // 1. Pengajuan dari topik yang ditawarkan dosen
            $pengajuanDariTopikDosen = TugasAkhir::whereIn('tawaran_topik_id', $topikIds)
                ->where('status', 'disetujui')
                ->with('mahasiswa.user', 'tawaranTopik')
                ->get();

            // 2. Pengajuan dari mahasiswa langsung ke dosen
            $pengajuanDariMahasiswa = TugasAkhir::whereNull('tawaran_topik_id')
                ->where('status', 'disetujui')
                ->whereHas('dosenPembimbing', function ($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                })
                ->with('mahasiswa.user', 'dosenPembimbing.user')
                ->get();

            // Gabungkan keduanya
            $semuaPengajuan = $pengajuanDariTopikDosen->merge($pengajuanDariMahasiswa);

            return $semuaPengajuan->unique('id')->values();
        }
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

        // 1. Eager load semua relasi yang dibutuhkan untuk efisiensi.
        $tugasAkhir->load([
            'peranDosenTa.dosen.user',
            'mahasiswa.user',
            'bimbinganTa',
            'dokumenTa'
        ]);

        // 2. Siapkan variabel dasar dari koleksi yang sudah di-load.
        $allBimbingan = $tugasAkhir->bimbinganTa;
        $allDokumen = $tugasAkhir->dokumenTa;
        $pembimbings = $tugasAkhir->peranDosenTa;

        $pembimbing1 = $pembimbings->firstWhere('peran', 'pembimbing1');
        $pembimbing2 = $pembimbings->firstWhere('peran', 'pembimbing2');

        // 3. Lakukan semua logika dan perhitungan di sini.
        $bimbinganCountP1 = $pembimbing1 ? $allBimbingan->where('dosen_id', $pembimbing1->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;
        $bimbinganCountP2 = $pembimbing2 ? $allBimbingan->where('dosen_id', $pembimbing2->dosen_id)->where('status_bimbingan', 'selesai')->count() : 0;

        $isDosenP1 = $pembimbing1 && $loggedInDosenId === $pembimbing1->dosen_id;
        $isDosenP2 = $pembimbing2 && $loggedInDosenId === $pembimbing2->dosen_id;

        $apakahSyaratBimbinganTerpenuhi = $bimbinganCountP1 >= 7 && ($pembimbing2 ? $bimbinganCountP2 >= 7 : true);

        // Ambil data pendaftaran sidang dan data untuk timeline/log.
        $pendaftaranTerbaru = PendaftaranSidang::where('tugas_akhir_id', $tugasAkhir->id)->latest()->first();
        $dokumenTerbaru = $allDokumen->last();

        $bimbinganIds = $allBimbingan->pluck('id');
        $catatanList = $bimbinganIds->isNotEmpty()
            ? CatatanBimbingan::whereIn('bimbingan_ta_id', $bimbinganIds)->with('author.user')->get()
            : collect();

        $timelineItems = collect($catatanList)->concat($allDokumen)->sortBy('created_at')->values();

        // 4. Kembalikan semua variabel yang dibutuhkan oleh SEMUA partial dalam satu array.
        return [
            // Data utama
            'tugasAkhir' => $tugasAkhir,
            'mahasiswa' => $tugasAkhir->mahasiswa,

            // Data untuk _panel_aksi
            'pembimbing1' => $pembimbing1,
            'pembimbing2' => $pembimbing2,
            'bimbinganCountP1' => $bimbinganCountP1,
            'bimbinganCountP2' => $bimbinganCountP2,
            'pendaftaranTerbaru' => $pendaftaranTerbaru,
            'isDosenP1' => $isDosenP1,
            'isDosenP2' => $isDosenP2,
            'apakahSyaratBimbinganTerpenuhi' => $apakahSyaratBimbinganTerpenuhi,

            // Data untuk _log_bimbingan dan modal riwayat
            'dokumenTerbaru' => $dokumenTerbaru,
            'timelineItems' => $timelineItems,
            'catatanList' => $catatanList, // Jika masih dibutuhkan secara terpisah
            'sesiAktif' => $allBimbingan->whereIn('status_bimbingan', ['disetujui', 'dijadwalkan', 'diajukan']),
        ];
    }

    // --- Sisa metode (setJadwal, createCatatan, dll) tidak perlu diubah ---
    // Logika di dalamnya sudah benar dan akan berfungsi dengan model yang baru.

    public function setJadwal(TugasAkhir $tugasAkhir, array $data): void
    {
        $this->authorizeDosenIsPembimbing($tugasAkhir);

        $pengajuanBimbingan = $tugasAkhir->bimbinganTa()
            ->where('status_bimbingan', 'diajukan')
            ->where('dosen_id', $this->dosen->id)
            ->firstOrFail();

        DB::transaction(function () use ($pengajuanBimbingan, $data) {
            $pengajuanBimbingan->update([
                'tanggal_bimbingan' => $data['tanggal_bimbingan'],
                'jam_bimbingan'     => $data['jam_bimbingan'],
                'status_bimbingan'  => 'dijadwalkan',
                'status_bimbingan'  => 'dijadwalkan',
            ]);

            // Tambahkan catatan otomatis
            CatatanBimbingan::create([
                'bimbingan_ta_id' => $pengajuanBimbingan->id,
                'author_type'     => 'App\Models\Dosen',
                'author_id'       => $this->dosen->id,
                'catatan'         => 'Bimbingan dimulai',
            ]);
        });
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

            // Update status bimbingan
            $bimbingan->update(['status_bimbingan' => 'selesai']);

            // Tambahkan catatan otomatis
            CatatanBimbingan::create([
                'bimbingan_ta_id' => $bimbingan->id,
                'author_type'     => 'App\Models\Dosen',
                'author_id'       => $this->dosen->id,
                'catatan'         => 'Bimbingan selesai',
            ]);

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

        // Ambil semua sesi dengan sesi_ke yang sama dan status 'dijadwalkan'
        $sesiTerkait = $bimbingan->tugasAkhir->bimbinganTa()
            ->where('sesi_ke', $bimbingan->sesi_ke)
            ->where('status_bimbingan', 'dijadwalkan')
            ->get();

        if ($sesiTerkait->isEmpty()) {
            throw new \Exception('Tidak ada jadwal aktif untuk dibatalkan.');
        }

        // Update semua sesi menjadi dibatalkan
        foreach ($sesiTerkait as $sesi) {
            $sesi->update(['status_bimbingan' => 'dibatalkan']);

            // Tambahkan catatan pembatalan otomatis oleh dosen
            $sesi->catatan()->create([
                'catatan'     => 'Bimbingan dibatalkan oleh dosen.',
                'author_type' => Dosen::class,
                'author_id'   => $this->dosen->id,
            ]);
        }
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
