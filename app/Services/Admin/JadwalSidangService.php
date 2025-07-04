<?php

namespace App\Services\Admin;

use App\Models\JadwalSidang;
use App\Models\PeranDosenTa;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JadwalSidangService
{
    /**
     * Mengambil semua data hitungan untuk dashboard sidang admin.
     * Menggunakan satu query untuk efisiensi.
     *
     * @return array
     */
    public function getDashboardCounts(): array
    {
        $counts = Sidang::query()
            ->select([
                DB::raw("SUM(CASE WHEN jenis_sidang = 'proposal' AND status IN ('menunggu', 'tidak_lulus') THEN 1 ELSE 0 END) as waitingSemproCount"),
                DB::raw("SUM(CASE WHEN jenis_sidang = 'akhir' AND status IN ('menunggu', 'tidak_lulus') THEN 1 ELSE 0 END) as waitingAkhirCount"),
                DB::raw("SUM(CASE WHEN jenis_sidang = 'proposal' AND status = 'dijadwalkan' THEN 1 ELSE 0 END) as scheduledSemproCount"),
                DB::raw("SUM(CASE WHEN jenis_sidang = 'akhir' AND status = 'dijadwalkan' THEN 1 ELSE 0 END) as scheduledAkhirCount"),
                DB::raw("SUM(CASE WHEN jenis_sidang = 'proposal' AND status IN ('lulus', 'lulus_revisi') THEN 1 ELSE 0 END) as pascaSemproCount"),
                DB::raw("SUM(CASE WHEN jenis_sidang = 'akhir' AND status IN ('lulus', 'lulus_revisi') THEN 1 ELSE 0 END) as pascaAkhirCount"),
            ])
            ->first()
            ->toArray();

        // Memastikan semua nilai adalah integer
        return array_map('intval', $counts);
    }

    /**
     * =========================================================================
     * PERBAIKAN: Method baru untuk mengambil semua daftar data untuk halaman
     * manajemen Sidang Akhir.
     * =========================================================================
     */
    public function getSidangAkhirLists(Request $request): array
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');
        $perPage = 10;

        // Query untuk Mahasiswa Menunggu Jadwal
        $mahasiswaMenunggu = Mahasiswa::query()
            ->whereHas('tugasAkhir.sidang', function ($q) {
                $q->where('status', 'menunggu')->where('jenis_sidang', 'akhir')->whereDoesntHave('jadwalSidang');
            })
            ->with(['user', 'tugasAkhir.sidangTerakhir'])
            ->when($prodi, fn($q) => $q->where('prodi', $prodi))
            ->when($search, fn($q) => $q->where(fn($q2) => $q2->where('nim', 'like', "%{$search}%")->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"))))
            ->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query());

        // Query untuk Mahasiswa Tidak Lulus
        $mahasiswaTidakLulus = Mahasiswa::query()
            ->whereHas('tugasAkhir.sidang', function ($q) {
                $q->where('jenis_sidang', 'akhir')->where('status', 'tidak_lulus');
            })
            ->with(['user', 'tugasAkhir.sidangTerakhir'])
            ->when($prodi, fn($q) => $q->where('prodi', $prodi))
            ->when($search, fn($q) => $q->where(fn($q2) => $q2->where('nim', 'like', "%{$search}%")->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"))))
            ->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query());

        // Query untuk Mahasiswa Lulus
        $mahasiswaLulus = Mahasiswa::query()
            ->whereHas('tugasAkhir.sidang', function ($q) {
                $q->where('jenis_sidang', 'akhir')->whereIn('status', ['lulus', 'lulus_revisi']);
            })
            ->with(['user', 'tugasAkhir.sidangTerakhir'])
            ->when($prodi, fn($q) => $q->where('prodi', $prodi))
            ->when($search, fn($q) => $q->where(fn($q2) => $q2->where('nim', 'like', "%{$search}%")->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"))))
            ->paginate($perPage, ['*'], 'lulus_page')->appends($request->query());

        // Query untuk Jadwal Sidang
        $jadwalMahasiswa = JadwalSidang::query()
            ->with(['sidang.tugasAkhir.mahasiswa.user', 'sidang.tugasAkhir.peranDosenTa.dosen.user', 'ruangan'])
            ->whereHas('sidang', function ($q) use ($prodi, $search) {
                $q->where('jenis_sidang', 'akhir')->where('status', 'dijadwalkan')
                    ->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
                        $q2->when($prodi, fn($q3) => $q3->where('prodi', $prodi))
                            ->when($search, fn($q3) => $q3->where(fn($q4) => $q4->where('nim', 'like', "%{$search}%")->orWhereHas('user', fn($q5) => $q5->where('name', 'like', "%{$search}%"))));
                    });
            })
            ->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

        // Data tambahan untuk view
        $dosen = Dosen::with('user')->get();
        $ruanganList = Ruangan::all();

        return compact(
            'mahasiswaMenunggu',
            'mahasiswaTidakLulus',
            'mahasiswaLulus',
            'jadwalMahasiswa',
            'dosen',
            'ruanganList'
        );
    }

    /**
     * =========================================================================
     * PERBAIKAN: Method baru untuk membuat jadwal sidang.
     * =========================================================================
     */
    public function createJadwal(array $data): JadwalSidang
    {
        // Cek bentrok jadwal ruangan di tanggal & waktu yang sama
        $isConflict = JadwalSidang::where('ruangan_id', $data['ruangan_id'])
            ->where('tanggal', $data['tanggal'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    // Waktu mulai berada di antara jadwal yang sudah ada
                    $q->where('waktu_mulai', '<=', $data['waktu_mulai'])
                        ->where('waktu_selesai', '>', $data['waktu_mulai']);
                })->orWhere(function ($q) use ($data) {
                    // Waktu selesai berada di antara jadwal yang sudah ada
                    $q->where('waktu_mulai', '<', $data['waktu_selesai'])
                        ->where('waktu_selesai', '>=', $data['waktu_selesai']);
                })->orWhere(function ($q) use ($data) {
                    // Jadwal yang sudah ada berada sepenuhnya di dalam jadwal baru
                    $q->where('waktu_mulai', '>=', $data['waktu_mulai'])
                        ->where('waktu_selesai', '<=', $data['waktu_selesai']);
                });
            })
            ->exists();

        if ($isConflict) {
            // Lemparkan exception jika terjadi bentrok
            throw new \Exception('Ruangan sudah terpakai pada waktu tersebut.');
        }

        return DB::transaction(function () use ($data) {
            $sidang = Sidang::findOrFail($data['sidang_id']);

            // Nonaktifkan sidang lain yang mungkin aktif untuk tugas akhir ini
            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('id', '!=', $sidang->id)
                ->update(['is_active' => false]);

            // Update status sidang saat ini
            $sidang->update([
                'status' => 'dijadwalkan',
                'is_active' => true,
            ]);

            // Buat jadwal sidang baru
            return JadwalSidang::create($data);
        });
    }

    /**
     * =========================================================================
     * PERBAIKAN: Method baru untuk memperbarui jadwal sidang dan penguji.
     * =========================================================================
     */
    public function updateJadwal(JadwalSidang $jadwal, array $data): array
    {
        // Gunakan transaksi database untuk memastikan semua operasi berhasil
        return DB::transaction(function () use ($jadwal, $data) {
            // 1. Update data jadwal sidang
            $jadwal->update([
                'tanggal' => $data['tanggalSidang'],
                'waktu_mulai' => $data['waktuMulai'],
                'waktu_selesai' => $data['waktuSelesai'],
                'ruangan_id' => $data['ruangan'],
            ]);

            // 2. Update dosen penguji
            $tugasAkhir = $jadwal->sidang->tugasAkhir;
            $peranList = ['penguji1', 'penguji2', 'penguji3', 'penguji4'];

            foreach ($peranList as $peran) {
                $dosenId = $data[$peran] ?? null;

                if ($dosenId) {
                    PeranDosenTa::updateOrCreate(
                        ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => $peran],
                        ['dosen_id' => $dosenId]
                    );
                } else {
                    // Jika input untuk peran ini tidak ada atau kosong, hapus dari database
                    PeranDosenTa::where('tugas_akhir_id', $tugasAkhir->id)->where('peran', $peran)->delete();
                }
            }

            // 3. Muat ulang relasi seperti di controller asli
            $jadwal->load(['ruangan', 'sidang.tugasAkhir.peranDosenTa.dosen.user']);

            // 4. Format data penguji untuk response JSON
            $penguji = $tugasAkhir->peranDosenTa
                ->whereIn('peran', $peranList)
                ->mapWithKeys(function ($pd) {
                    return [$pd->peran => optional($pd->dosen->user)->name ?? '-'];
                });

            // 5. Kembalikan data dalam format yang sama persis seperti controller asli
            return [
                'jadwal' => $jadwal,
                'penguji' => $penguji,
            ];
        });
    }

    /**
     * =========================================================================
     * PERBAIKAN: Method baru untuk menetapkan dosen penguji.
     * =========================================================================
     */
    public function assignPenguji(Sidang $sidang, array $pengujiData): void
    {
        DB::transaction(function () use ($sidang, $pengujiData) {
            $tugasAkhirId = $sidang->tugas_akhir_id;

            // 1. Hapus semua peran penguji yang lama untuk tugas akhir ini
            PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)
                ->where('peran', 'like', 'penguji%')
                ->delete();

            // 2. Buat peran penguji yang baru berdasarkan data dari request
            foreach ($pengujiData['penguji'] as $index => $dosenId) {
                PeranDosenTa::create([
                    'dosen_id' => $dosenId,
                    'tugas_akhir_id' => $tugasAkhirId,
                    'peran' => 'penguji' . ($index + 1),
                ]);
            }
        });
    }

    /**
     * Mengambil dan memproses semua data yang diperlukan 
     * untuk tampilan detail jadwal sidang.
     *
     * @param int $sidang_id
     * @return array
     */
    public function getJadwalDetailsForView($sidang_id): array
    {
        // 1. Ambil data jadwal utama dengan relasi yang dibutuhkan
        $jadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'ruangan',
            'sidang.tugasAkhir.peranDosenTa.dosen.user'
        ])->where('sidang_id', $sidang_id)->firstOrFail();

        // 2. Proses data peran dosen agar mudah diakses di view
        $peranDosen = $jadwal->sidang->tugasAkhir->peranDosenTa->mapWithKeys(function ($item) {
            return [$item->peran => $item->dosen];
        });

        // 3. Ambil data pendukung untuk form/dropdown
        $dosens = Dosen::with('user')->get();
        $ruangans = Ruangan::all();

        // 4. Kembalikan semua data dalam satu array asosiatif
        return [
            'jadwal'     => $jadwal,
            'peranDosen' => $peranDosen,
            'dosens'     => $dosens,
            'ruangans'   => $ruangans,
        ];
    }

    /**
     * Menandai status akhir dari sebuah sidang.
     *
     * @param int $sidang_id
     * @param string $status
     * @return Sidang
     * @throws ModelNotFoundException|\Exception
     */
    public function tandaiStatusAkhir(int $sidang_id, string $status): Sidang
    {
        return DB::transaction(function () use ($sidang_id, $status) {
            // lockForUpdate() mencegah race condition jika ada 2 admin menandai bersamaan
            $sidang = Sidang::where('id', $sidang_id)
                ->where('jenis_sidang', 'akhir')
                ->lockForUpdate()
                ->firstOrFail();

            // Cek apakah sidang sudah pernah ditandai
            if (in_array($sidang->status, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
                // Melempar exception agar bisa ditangkap di controller
                throw new HttpException(422, 'Sidang sudah ditandai sebelumnya.');
            }

            $sidang->status = $status;
            if ($status === 'tidak_lulus') {
                $sidang->is_active = false;
            }

            $sidang->save();

            return $sidang;
        });
    }

    // Method lain untuk SidangAkhir, store, update, dll. akan ditambahkan di sini nanti.
}
