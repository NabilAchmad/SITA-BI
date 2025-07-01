<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;
use App\Services\Mahasiswa\TugasAkhirService;
use Illuminate\Http\Request;

class TugasAkhirController extends Controller
{
    protected TugasAkhirService $tugasAkhirService;

    // Service di-inject secara otomatis oleh Laravel (Dependency Injection)
    public function __construct(TugasAkhirService $tugasAkhirService)
    {
        $this->tugasAkhirService = $tugasAkhirService;
    }

    public function dashboard()
    {
        // Logika dashboard bisa saja berbeda, tapi idealnya juga memanggil service
        $tugasAkhir = $this->tugasAkhirService->getActiveTugasAkhirForProgressPage();
        $sudahMengajukan = (bool)$tugasAkhir;

        return view('mahasiswa.tugas-akhir.dashboard.dashboard', compact('tugasAkhir', 'sudahMengajukan'));
    }

    public function progress()
    {
        // 1. Panggil service untuk mendapatkan data TA yang aktif
        $tugasAkhir = $this->tugasAkhirService->getActiveTugasAkhirForProgressPage();

        // 2. Inisialisasi variabel pembimbingList sebagai collection kosong
        //    Ini adalah kunci perbaikannya.
        $pembimbingList = collect();

        // 3. Jika tugas akhir ada, isi pembimbingList menggunakan accessor yang sudah kita buat
        if ($tugasAkhir) {
            // Kita tidak perlu lagi melakukan query, cukup akses atribut virtual!
            if ($tugasAkhir->pembimbing_satu) {
                $pembimbingList->push($tugasAkhir->pembimbing_satu);
            }
            if ($tugasAkhir->pembimbing_dua) {
                $pembimbingList->push($tugasAkhir->pembimbing_dua);
            }
        }

        // 4. Kirim semua data yang diperlukan ke view
        return view('mahasiswa.tugas-akhir.crud-ta.progress', [
            'tugasAkhir'      => $tugasAkhir,
            'isMengajukanTA'  => (bool)$tugasAkhir,
            'progress'        => $tugasAkhir?->progress_percentage ?? 0,
            'pembimbingList'  => $pembimbingList, // <- Variabel ini sekarang selalu ada (exist)

            // Variabel lain seperti revisi, bimbingan, dokumen, dll.,
            // dapat diakses langsung dari $tugasAkhir di dalam view karena sudah di-load oleh service.
            // Contoh di Blade: $tugasAkhir?->revisiTa
        ]);
    }

    public function ajukanForm()
    {
        // View ini tidak butuh data kompleks, jadi bisa langsung
        return view('mahasiswa.tugas-akhir.crud-ta.create');
    }

    public function store(Request $request)
    {
        // Sebaiknya gunakan FormRequest untuk validasi yang lebih bersih
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
        ]);

        try {
            $this->tugasAkhirService->createTugasAkhir($validatedData);
            return redirect()->route('tugas-akhir.dashboard')->with('success', 'Tugas Akhir berhasil diajukan!');
        } catch (\Exception $e) {
            // Tangkap error dari service dan tampilkan ke user
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function cancel(Request $request, TugasAkhir $tugasAkhir)
    {
        // Laravel's Route-Model Binding akan otomatis menemukan TA berdasarkan ID
        try {
            $this->tugasAkhirService->requestCancellation($tugasAkhir, $request->input('alasan'));

            return redirect()->route('tugas-akhir.progress')->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pengajuan pembatalan Tugas Akhir telah dikirim.'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => $e->getMessage()
            ]);
        }
    }
    
    public function showCancelled()
    {
        // Mengambil semua Tugas Akhir yang sudah dibatalkan
        $tugasAkhirDibatalkan = $this->tugasAkhirService->getCancelledTugasAkhir();

        return view('mahasiswa.tugas-akhir.crud-ta.cancel', [
            'tugasAkhirDibatalkan' => $tugasAkhirDibatalkan,
        ]);
    }
}
