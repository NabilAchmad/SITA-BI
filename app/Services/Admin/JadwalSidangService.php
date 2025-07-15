<?php

namespace App\Services\Admin;

use App\Models\Dosen;
use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use App\Models\PeranDosenTa;
use App\Models\Ruangan;
use App\Models\Sidang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JadwalSidangService
{
    /**
     * ✅ PERBAIKAN: Mengambil data hitungan untuk dashboard sidang.
     * Logika tidak lagi bergantung pada 'jenis_sidang' dan 'status'.
     *
     * @return array
     */
    public function getDashboardCounts(): array
    {
        // Query langsung ke tabel sidang tanpa filter jenis_sidang
        $counts = Sidang::query()
            ->select([
                // Menggunakan status_hasil untuk menghitung
                DB::raw("SUM(CASE WHEN status_hasil = 'dijadwalkan' THEN 1 ELSE 0 END) as scheduledAkhirCount"),
                DB::raw("SUM(CASE WHEN status_hasil IN ('lulus', 'lulus_revisi') THEN 1 ELSE 0 END) as pascaAkhirCount"),
                DB::raw("SUM(CASE WHEN status_hasil = 'tidak_lulus' THEN 1 ELSE 0 END) as waitingAkhirCount"), // Diasumsikan 'menunggu' adalah yang belum dijadwalkan
            ])
            ->first()
            ->toArray();

        // Memastikan semua nilai adalah integer
        return array_map('intval', $counts);
    }

    /**
     * ✅ PERBAIKAN: Mengambil semua daftar data untuk manajemen Sidang.
     * Logika tidak lagi bergantung pada 'jenis_sidang' dan 'status'.
     *
     * @param Request $request
     * @return array
     */
    public function getSidangAkhirLists(Request $request): array
    {
        $prodi = $request->input('prodi');
        $search = $request->input('search');
        $perPage = 10;

        // Base query untuk mahasiswa berdasarkan status_hasil sidang
        $baseQuery = function ($status) use ($prodi, $search) {
            return Mahasiswa::query()
                ->whereHas('tugasAkhir.sidang', function ($q) use ($status) {
                    // Langsung filter berdasarkan status_hasil
                    $q->whereIn('status_hasil', (array) $status);
                })
                ->with(['user', 'tugasAkhir.sidang']) // Cukup load sidang
                ->when($prodi, fn($q) => $q->where('prodi', $prodi))
                ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"));
                }));
        };

        // Query untuk Jadwal Sidang yang status_hasilnya 'dijadwalkan'
        $jadwalMahasiswa = JadwalSidang::query()
            ->with(['sidang.tugasAkhir.mahasiswa.user', 'sidang.tugasAkhir.peranDosenTa.dosen.user', 'ruangan'])
            ->whereHas('sidang', function ($q) use ($prodi, $search) {
                // Filter utama untuk jadwal aktif
                $q->where('status_hasil', 'dijadwalkan');

                // Filter tambahan berdasarkan prodi dan search
                $q->whereHas('tugasAkhir.mahasiswa', function ($q2) use ($prodi, $search) {
                    $q2->when($prodi, fn($q3) => $q3->where('prodi', $prodi))
                        ->when($search, fn($q3) => $q3->where(function ($q4) use ($search) {
                            $q4->where('nim', 'like', "%{$search}%")
                                ->orWhereHas('user', fn($q5) => $q5->where('name', 'like', "%{$search}%"));
                        }));
                });
            })
            ->paginate($perPage, ['*'], 'jadwal_page')->appends($request->query());

        // Data tambahan untuk view
        $dosen = Dosen::with('user')->get();
        $ruanganList = Ruangan::all();

        return [
            // Mengambil mahasiswa yang sidangnya belum dijadwalkan (menunggu approval dosen)
            'mahasiswaMenunggu' => Mahasiswa::query()->whereHas('tugasAkhir.pendaftaranSidang', fn($q) => $q->where('status_pembimbing_1', '!=', 'disetujui')->orWhere('status_pembimbing_2', '!=', 'disetujui'))->paginate($perPage, ['*'], 'menunggu_page')->appends($request->query()),
            'mahasiswaTidakLulus' => $baseQuery('tidak_lulus')->paginate($perPage, ['*'], 'tidaklulus_page')->appends($request->query()),
            'mahasiswaLulus' => $baseQuery(['lulus', 'lulus_revisi'])->paginate($perPage, ['*'], 'lulus_page')->appends($request->query()),
            'jadwalMahasiswa' => $jadwalMahasiswa,
            'dosen' => $dosen,
            'ruanganList' => $ruanganList,
        ];
    }

    /**
     * ✅ PERBAIKAN: Membuat jadwal sidang baru.
     * Tidak lagi mengupdate status sidang, karena status diatur saat pembuatan.
     *
     * @param array $data
     * @return JadwalSidang
     * @throws \Exception
     */
    public function createJadwal(array $data): JadwalSidang
    {
        // Cek bentrok jadwal ruangan (Logika ini sudah benar)
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

        // Cukup membuat jadwal, status sidang tidak perlu diubah di sini
        return JadwalSidang::create($data);
    }

    // ... (Fungsi updateJadwal, assignPenguji, getJadwalDetailsForView tidak perlu diubah)
    // ... (Pastikan fungsi-fungsi ini dipastikan ada di file Anda)
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
    /**
     * ✅ PERBAIKAN: Menandai hasil akhir dari sebuah sidang.
     * Menggunakan kolom 'status_hasil' bukan 'status'.
     *
     * @param int $sidang_id
     * @param string $status_hasil
     * @return Sidang
     */
    public function tandaiStatusAkhir(int $sidang_id, string $status_hasil): Sidang
    {
        return DB::transaction(function () use ($sidang_id, $status_hasil) {
            $sidang = Sidang::where('id', $sidang_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Cek apakah sidang sudah memiliki hasil akhir
            if (in_array($sidang->status_hasil, ['lulus', 'lulus_revisi', 'tidak_lulus'])) {
                throw new HttpException(422, 'Sidang sudah memiliki hasil akhir sebelumnya.');
            }

            // Update kolom status_hasil
            $sidang->status_hasil = $status_hasil;

            // Jika tidak lulus, sidang menjadi tidak aktif lagi
            if ($status_hasil === 'tidak_lulus') {
                $sidang->is_active = false;
            }
            $sidang->save();

            return $sidang;
        });
    }
}
