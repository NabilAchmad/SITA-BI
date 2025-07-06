<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalSemproRequest;
use App\Http\Requests\Admin\UpdateStatusSemproRequest;
use App\Repositories\SeminarProposalRepository;
use App\Services\Admin\JadwalSidangSemproService;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\Sidang;
use App\Models\TugasAkhir;
use App\Models\JadwalSidang;
use App\Models\PeranDosenTa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk transaction
use App\Http\Requests\Admin\UpdateJadwalSemproRequest;

class JadwalSidangSemproController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(SeminarProposalRepository $repository, JadwalSidangSemproService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Menampilkan halaman utama manajemen jadwal sempro.
     */
    public function index()
    {
        $mahasiswaMenunggu = $this->repository->getWaitingForSchedule();
        $jadwalMahasiswa = $this->repository->getScheduled();
        $mahasiswaLulusSempro = $this->repository->getPassed();
        $mahasiswaTidakLulus = $this->repository->getFailed();

        $dosen = Dosen::with('user')->get();
        $ruanganList = Ruangan::all();

        return view('admin.sidang.sempro.views.mhs-sidang', compact(
            'mahasiswaMenunggu',
            'jadwalMahasiswa',
            'mahasiswaLulusSempro',
            'mahasiswaTidakLulus',
            'dosen',
            'ruanganList'
        ));
    }

    /**
     * Menyimpan jadwal sidang baru.
     */
    public function store(StoreJadwalSemproRequest $request)
    {
        try {
            $this->service->schedule($request->validated());

            return redirect()->route('sidang.kelola.sempro')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Jadwal sidang berhasil disimpan.'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Menandai status sidang.
     */
    public function updateStatus(UpdateStatusSemproRequest $request, Sidang $sidang)
    {
        try {
            $this->service->markAsFinished($sidang, $request->validated()['status']);

            return redirect()->route('sidang.kelola.sempro')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Status sidang berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Menyimpan dosen penguji untuk sebuah sidang.
     */
    public function simpanPenguji(Request $request, $tugas_akhir_id)
    {
        $request->validate([
            // PERBAIKAN: Minimal penguji sekarang adalah 2
            'penguji'   => 'required|array|min:2|max:4',
            'penguji.*' => 'required|exists:dosen,id',
        ]);

        try {
            // Gunakan transaction untuk memastikan semua query berhasil atau tidak sama sekali
            $sidang = DB::transaction(function () use ($request, $tugas_akhir_id) {
                $tugasAkhir = TugasAkhir::findOrFail($tugas_akhir_id);

                // 1. Buat atau dapatkan record Sidang
                $sidang = Sidang::firstOrCreate(
                    [
                        'tugas_akhir_id' => $tugasAkhir->id,
                        'jenis_sidang'   => 'proposal',
                    ],
                    [
                        'status'    => 'menunggu',
                        'is_active' => true,
                    ]
                );

                // 2. Hapus data penguji lama untuk tugas akhir ini agar tidak ada duplikasi
                PeranDosenTA::where('tugas_akhir_id', $tugasAkhir->id)
                    ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
                    ->delete();

                // 3. Simpan data penguji yang baru satu per satu
                foreach ($request->penguji as $index => $dosenId) {
                    PeranDosenTA::create([
                        'dosen_id'       => $dosenId,
                        'tugas_akhir_id' => $tugasAkhir->id,
                        'peran'          => 'penguji' . ($index + 1), // Menghasilkan 'penguji1', 'penguji2', dst.
                    ]);
                }

                return $sidang; // Kembalikan model sidang untuk diambil ID-nya
            });

            // Berhasil, kirim kembali response JSON dengan sidang_id
            return response()->json([
                'success'   => true,
                'message'   => 'Dosen penguji berhasil disimpan.',
                'sidang_id' => $sidang->id, // Kunci untuk memicu modal kedua
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman detail untuk sebuah jadwal sidang sempro.
     *
     * @param  int  $sidang_id
     * @return \Illuminate\View\View
     */
    public function show($sidang_id)
    {
        // 1. Ambil data jadwal spesifik berdasarkan foreign key 'sidang_id'.
        // Ini lebih sesuai dengan route dan cara data di-link dari view.
        $jadwal = JadwalSidang::with([
            'sidang.tugasAkhir.mahasiswa.user',
            'sidang.tugasAkhir.peranDosenTa.dosen.user',
            'ruangan'
        ])->where('sidang_id', $sidang_id)->firstOrFail();

        // 2. Ambil semua data dosen untuk mengisi dropdown di form edit.
        $dosens = Dosen::with('user')->get();

        // 3. Ambil semua data ruangan untuk mengisi dropdown di form edit.
        $ruangans = Ruangan::all();

        // 4. Kirim semua data yang dibutuhkan ke view.
        return view('admin.sidang.sempro.modal.detail-jadwal', compact('jadwal', 'dosens', 'ruangans'));
    }

    /**
     * Memperbarui data jadwal sidang yang ada di database.
     * Fungsi ini dipanggil oleh JavaScript (AJAX) saat tombol "Simpan Perubahan" diklik.
     *
     * @param  UpdateJadwalSemproRequest $request
     * @param  JadwalSidang $jadwal
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateJadwalSemproRequest $request, JadwalSidang $jadwal)
    {
        try {
            $pengujiNames = DB::transaction(function () use ($request, $jadwal) {
                // 1. Update data utama di tabel jadwal_sidang
                $jadwal->update([
                    'tanggal'       => $request->tanggalSidang,
                    'waktu_mulai'   => $request->waktuMulai,
                    'waktu_selesai' => $request->waktuSelesai,
                    'ruangan_id'    => $request->ruangan,
                ]);

                // 2. Update data dosen penguji
                $tugasAkhir = $jadwal->sidang->tugasAkhir;
                if (!$tugasAkhir) {
                    throw new \Exception('Data tugas akhir terkait tidak ditemukan.');
                }

                $pengujiIds = array_filter($request->penguji);

                PeranDosenTA::where('tugas_akhir_id', $tugasAkhir->id)
                    ->whereIn('peran', ['penguji1', 'penguji2', 'penguji3', 'penguji4'])
                    ->delete();

                $newPengujiNames = [];
                foreach ($pengujiIds as $index => $dosenId) {
                    $peran = 'penguji' . ($index + 1);
                    $peranDosen = PeranDosenTA::create([
                        'dosen_id'       => $dosenId,
                        'tugas_akhir_id' => $tugasAkhir->id,
                        'peran'          => $peran,
                    ]);
                    $newPengujiNames[$peran] = $peranDosen->dosen->user->name;
                }

                return $newPengujiNames;
            });

            // [PERBAIKAN] Muat ulang relasi 'ruangan' secara eksplisit SEBELUM mengirim respons.
            // Ini memastikan objek 'ruangan' akan disertakan dalam JSON.
            $jadwal->load('ruangan');

            // Berhasil, kirim kembali data yang sudah diperbarui dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'Jadwal sidang berhasil diperbarui.',
                'jadwal'  => $jadwal, // Sekarang $jadwal sudah berisi data ruangan
                'penguji' => $pengujiNames,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(JadwalSidang $jadwal)
    {
        try {
            DB::transaction(function () use ($jadwal) {
                // Set status sidang kembali ke 'menunggu'
                $jadwal->sidang->update(['status' => 'menunggu']);
                // Hapus jadwal
                $jadwal->delete();
            });
            return redirect()->route('admin.jadwal-sempro.index')->with('alert', ['type' => 'success', 'message' => 'Jadwal berhasil dihapus.']);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', ['type' => 'error', 'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()]);
        }
    }

    // Method show() bisa tetap sama atau disederhanakan dengan repository
    // public function show($sidang_id) { ... }
}
