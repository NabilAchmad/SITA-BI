<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTa;
use App\Models\Ruangan;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JadwalSidangService
{
    /**
     * ✅ PERBAIKAN: Mengambil data hitungan untuk dashboard sidang.
     * Logika 'waiting' sekarang menghitung sidang yang statusnya 'menunggu_penjadwalan'.
     */
    public function getDashboardCounts(): array
    {
        $counts = Sidang::query()
            ->select([
                // Menghitung sidang yang menunggu untuk dijadwalkan oleh admin
                DB::raw("SUM(CASE WHEN status_hasil = 'menunggu_penjadwalan' THEN 1 ELSE 0 END) as waitingAkhirCount"),
                // Menghitung sidang yang sudah memiliki jadwal
                DB::raw("SUM(CASE WHEN status_hasil = 'dijadwalkan' THEN 1 ELSE 0 END) as scheduledAkhirCount"),
                // Menghitung sidang yang sudah selesai (lulus)
                DB::raw("SUM(CASE WHEN status_hasil IN ('lulus', 'lulus_revisi') THEN 1 ELSE 0 END) as pascaAkhirCount"),
            ])
            ->first()
            ->toArray();

        return array_map('intval', $counts);
    }

    /**
     * ✅ PERBAIKAN: Mengambil semua daftar data untuk manajemen Sidang.
     * Logika 'mahasiswaMenunggu' sekarang mencari mahasiswa yang sidangnya siap dijadwalkan.
     */
    public function getSidangAkhirLists(Request $request): array
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');
        $perPage = 10;

        // Base query untuk mengambil mahasiswa berdasarkan status_hasil sidang mereka.
        $baseQuery = function ($status) use ($prodi, $search) {
            return Mahasiswa::query()
                ->whereHas('tugasAkhir.sidang', function ($q) use ($status) {
                    $q->whereIn('status_hasil', (array) $status);
                })
                ->with(['user', 'tugasAkhir.sidang'])
                ->when($prodi, fn($q) => $q->where('prodi', $prodi))
                ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"));
                }));
        };

        // Query untuk mahasiswa yang sudah memiliki jadwal sidang aktif
        $jadwalMahasiswa = JadwalSidang::query()
            ->with(['sidang.tugasAkhir.mahasiswa.user', 'sidang.tugasAkhir.peranDosenTa.dosen.user', 'ruangan'])
            ->whereHas('sidang', function ($q) use ($prodi, $search) {
                $q->where('status_hasil', 'dijadwalkan');
                $q->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
                    $q2->when($prodi, fn($q3) => $q3->where('prodi', $prodi))
                        ->when($search, fn($q3) => $q3->where(function ($q4) use ($search) {
                            $q4->where('nim', 'like', "%{$search}%")
                                ->orWhereHas('user', fn($q5) => $q5->where('name', 'like', "%{$search}%"));
                        }));
                });
            })
            ->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

        return [
            // LOGIKA BARU: Mengambil mahasiswa yang sidangnya berstatus 'menunggu_penjadwalan'
            'mahasiswaMenunggu' => $baseQuery('menunggu_penjadwalan')->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query()),
            'mahasiswaTidakLulus' => $baseQuery('tidak_lulus')->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query()),
            'mahasiswaLulus' => $baseQuery(['lulus', 'lulus_revisi'])->paginate($perPage, ['*'], 'lulus_page')->appends($request->query()),
            'jadwalMahasiswa' => $jadwalMahasiswa,
            'dosen' => Dosen::with('user')->get(),
            'ruanganList' => Ruangan::all(),
        ];
    }

    /**
     * ✅ PERBAIKAN: Membuat jadwal sidang baru dan mengupdate status sidang.
     * Proses ini sekarang dibungkus dalam transaksi database.
     */
    public function createJadwal(array $data): JadwalSidang
    {
        return DB::transaction(function () use ($data) {
            // Cek bentrok jadwal ruangan
            $isConflict = JadwalSidang::where('ruangan_id', $data['ruangan_id'])
                ->where('tanggal', $data['tanggal'])
                ->where(function ($query) use ($data) {
                    $query->where(fn($q) => $q->where('waktu_mulai', '<=', $data['waktu_mulai'])->where('waktu_selesai', '>', $data['waktu_mulai']))
                        ->orWhere(fn($q) => $q->where('waktu_mulai', '<', $data['waktu_selesai'])->where('waktu_selesai', '>=', $data['waktu_selesai']))
                        ->orWhere(fn($q) => $q->where('waktu_mulai', '>=', $data['waktu_mulai'])->where('waktu_selesai', '<=', $data['waktu_selesai']));
                })
                ->exists();

            if ($isConflict) {
                throw new \Exception('Ruangan sudah terpakai pada waktu tersebut.');
            }

            // 1. Buat jadwal baru
            $jadwal = JadwalSidang::create($data);

            // 2. Update status sidang terkait menjadi 'dijadwalkan'
            $sidang = Sidang::findOrFail($data['sidang_id']);
            $sidang->status_hasil = 'dijadwalkan';
            $sidang->save();

            return $jadwal;
        });
    }

    // ... (Fungsi updateJadwal, assignPenguji, getJadwalDetailsForView tidak perlu diubah)
    // ... (Pastikan fungsi-fungsi ini ada di file Anda)
    public function updateJadwal(JadwalSidang $jadwal, array $data): array
    {
        return DB::transaction(function () use ($jadwal, $data) {
            $jadwal->update([
                'tanggal' => $data['tanggalSidang'],
                'waktu_mulai' => $data['waktuMulai'],
                'waktu_selesai' => $data['waktuSelesai'],
                'ruangan_id' => $data['ruangan'],
            ]);

            $tugasAkhir = $jadwal->sidang->tugasAkhir;
            $peranList = ['penguji1', 'penguji2', 'penguji3', 'penguji4'];

            foreach ($peranList as $peran) {
                $dosenId = $data[$peran] ?? null;
                PeranDosenTa::updateOrCreate(
                    ['tugas_akhir_id' => $tugasAkhir->id, 'peran' => $peran],
                    ['dosen_id' => $dosenId]
                );
            }

            $jadwal->load(['ruangan', 'sidang.tugasAkhir.peranDosenTa.dosen.user']);

            $penguji = $tugasAkhir->peranDosenTa
                ->whereIn('peran', $peranList)
                ->mapWithKeys(fn($pd) => [$pd->peran => optional($pd->dosen->user)->name ?? '-']);

            return ['jadwal' => $jadwal, 'penguji' => $penguji];
        });
    }

    public function assignPenguji(Sidang $sidang, array $pengujiData): void
    {
        DB::transaction(function () use ($sidang, $pengujiData) {
            $tugasAkhirId = $sidang->tugas_akhir_id;
            PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)->where('peran', 'like', 'penguji%')->delete();

            foreach ($pengujiData['penguji'] as $index => $dosenId) {
                PeranDosenTa::create([
                    'dosen_id' => $dosenId,
                    'tugas_akhir_id' => $tugasAkhirId,
                    'peran' => 'penguji' . ($index + 1),
                ]);
            }
        });
    }

    public function getJadwalDetailsForView(int $sidang_id): array
    {
        $jadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'ruangan',
            'sidang.tugasAkhir.peranDosenTa.dosen.user'
        ])->where('sidang_id', $sidang_id)->firstOrFail();

        $peranDosen = $jadwal->sidang->tugasAkhir->peranDosenTa->mapWithKeys(fn($item) => [$item->peran => $item->dosen]);

        return [
            'jadwal' => $jadwal,
            'peranDosen' => $peranDosen,
            'dosens' => Dosen::with('user')->get(),
            'ruangans' => Ruangan::all(),
        ];
    }

    public function tandaiStatusAkhir(int $sidang_id, string $status_hasil): Sidang
    {
        return DB::transaction(function () use ($sidang_id, $status_hasil) {
            $sidang = Sidang::where('id', $sidang_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($sidang->status_hasil, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
                throw new HttpException(422, 'Sidang sudah memiliki hasil akhir sebelumnya.');
            }

            $sidang->status_hasil = $status_hasil;

            if ($status_hasil === 'tidak_lulus') {
                $sidang->is_active = false;
            }
            $sidang->save();

            return $sidang;
        });
    }
}
