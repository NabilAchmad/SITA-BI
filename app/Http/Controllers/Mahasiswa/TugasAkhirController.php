<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\{TugasAkhir, Mahasiswa, BimbinganTA, RevisiTa, DokumenTa, Sidang, File};
use Illuminate\Support\Facades\{Storage, Auth};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class TugasAkhirController extends Controller
{
    private function assumedMahasiswa()
    {
        return Auth::user()->mahasiswa;
    }

    public function dashboard()
    {
        $mahasiswa = $this->assumedMahasiswa();
        // Mengambil tugas akhir yang aktif (bukan yang dibatalkan atau sudah selesai)
        $tugasAkhir = $mahasiswa->tugasAkhir()->whereNotIn('status', ['dibatalkan', 'selesai'])->latest()->first();
        $sudahMengajukan = $tugasAkhir !== null;

        return view('mahasiswa.tugas-akhir.dashboard.dashboard', compact('tugasAkhir', 'sudahMengajukan', 'mahasiswa'));
    }

    public function ajukanForm()
    {
        $mahasiswa = $this->assumedMahasiswa();

        // **MODIFIKASI**: Memeriksa apakah ada tugas akhir yang masih aktif.
        // Mahasiswa bisa mengajukan lagi jika TA sebelumnya sudah 'dibatalkan' atau 'selesai'.
        $tugasAkhirAktif = $mahasiswa->tugasAkhir()->whereNotIn('status', ['dibatalkan', 'selesai'])->exists();

        if ($tugasAkhirAktif) {
            return redirect()->route('tugas-akhir.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Pengajuan Gagal',
                'text' => 'Anda masih memiliki tugas akhir yang aktif. Selesaikan atau batalkan terlebih dahulu.'
            ]);
        }

        return view('mahasiswa.tugas-akhir.crud-ta.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $mahasiswa = $this->assumedMahasiswa();

        $tugasAkhirAktif = $mahasiswa->tugasAkhir()->whereNotIn('status', ['dibatalkan', 'selesai'])->exists();

        if ($tugasAkhirAktif) {
            return redirect()->route('tugas-akhir.dashboard')->with('alert', [
                'type' => 'error',
                'title' => 'Pengajuan Gagal',
                'text' => 'Anda masih memiliki tugas akhir yang aktif.'
            ]);
        }

        $request->validate([
            'judul' => 'required|string|max:255|unique:tugas_akhir,judul,NULL,id,mahasiswa_id,' . $mahasiswa->id,
            'abstrak' => 'required|string',
        ]);

        TugasAkhir::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'status' => 'diajukan',
            'tanggal_pengajuan' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('tugas-akhir.dashboard')->with('success', 'Tugas Akhir berhasil diajukan!');
    }

    public function progress()
    {
        $mahasiswa = $this->assumedMahasiswa();

        // 1. Tetap cari tugas akhir yang aktif. Jika tidak ada, $tugasAkhir akan bernilai null.
        $tugasAkhir = $mahasiswa->tugasAkhir()
            ->whereNotIn('status', ['dibatalkan', 'selesai'])
            ->with(['peranDosenTa' => function ($query) {
                $query->whereIn('peran', ['pembimbing1', 'pembimbing2'])
                    ->with('dosen.user');
            }])
            ->latest()
            ->first();

        // 2. Tentukan nilai variabel berdasarkan keberadaan $tugasAkhir
        $isMengajukanTA = $tugasAkhir !== null;
        $progress = 0;

        // Inisialisasi dengan collection kosong untuk mencegah error di view
        $progressBimbingan = collect();
        $revisi = collect();
        $dokumen = collect();
        $sidang = collect();
        $pembimbingList = collect();

        // 3. Jika tugas akhir DITEMUKAN, isi variabel dengan data yang sebenarnya
        if ($tugasAkhir) {
            $progressBimbingan = BimbinganTA::where('tugas_akhir_id', $tugasAkhir->id)
                ->latest('tanggal_bimbingan')
                ->get();

            $revisi = RevisiTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get();
            $dokumen = DokumenTa::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get();
            $sidang = Sidang::where('tugas_akhir_id', $tugasAkhir->id)->latest()->get();
            $pembimbingList = $tugasAkhir->peranDosenTa ?? collect();

            $jumlahBimbingan = $progressBimbingan->count();

            $progress = match ($tugasAkhir->status) {
                'diajukan' => min(ceil(($jumlahBimbingan / 7) * 100), 49),
                'disetujui' => 50 + min(ceil(($jumlahBimbingan / 7) * 50), 49),
                'selesai', 'lulus_tanpa_revisi', 'lulus_dengan_revisi' => 100,
                'draft', 'menunggu_pembatalan' => 0,
                default => 0,
            };
        }

        // 4. Kirim semua variabel ke view. View akan menerima $tugasAkhir sebagai null
        //    atau berisi data, dan variabel lain akan berisi data atau collection kosong.
        return view('mahasiswa.tugas-akhir.crud-ta.progress', compact(
            'tugasAkhir',
            'progressBimbingan',
            'revisi',
            'dokumen',
            'sidang',
            'isMengajukanTA',
            'progress',
            'pembimbingList'
        ));
    }

    public function cancel(Request $request, $id)
    {
        $mahasiswa = $this->assumedMahasiswa();
        $tugasAkhir = TugasAkhir::with('peranDosenTa')->find($id);

        // **PENAMBAHAN**: Pengecekan awal jika mahasiswa mencoba mengakses URL cancel tanpa punya TA sama sekali
        if (!$mahasiswa->tugasAkhir()->exists()) {
            return redirect()->route('dashboard.mahasiswa')->with('alert', [
                'type' => 'error',
                'title' => 'Data Tidak Ditemukan',
                'text' => 'Data tugas akhir belum tersedia.'
            ]);
        }

        // Jika TA dengan ID spesifik tidak ditemukan
        if (!$tugasAkhir) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Tidak Ditemukan',
                'text' => 'Tugas akhir yang ingin Anda batalkan tidak ditemukan.'
            ]);
        }

        if ($tugasAkhir->mahasiswa_id !== $mahasiswa->id) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'text' => 'Anda tidak memiliki akses untuk membatalkan tugas akhir ini.'
            ]);
        }

        if (in_array($tugasAkhir->status, ['draft', 'dibatalkan', 'selesai'])) {
            return back()->with('alert', [
                'type' => 'info',
                'title' => 'Tidak Dapat Dibatalkan',
                'text' => 'Tugas akhir dengan status ini tidak dapat dibatalkan.'
            ]);
        }

        $tugasAkhir->update([
            'status' => 'menunggu_pembatalan',
            'alasan_pembatalan' => $request->input('alasan'),
        ]);

        foreach ($tugasAkhir->peranDosenTa as $peran) {
            if (in_array($peran->peran, ['pembimbing1', 'pembimbing2'])) {
                $peran->update([
                    'setuju_pembatalan' => null,
                    'tanggal_verifikasi' => null,
                    'catatan_verifikasi' => null,
                ]);
            }
        }

        return redirect()->route('tugas-akhir.progress')->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Pengajuan pembatalan Tugas Akhir telah dikirim dan menunggu verifikasi dosen pembimbing.'
        ]);
    }

    public function showCancelled()
    {
        $mahasiswa = $this->assumedMahasiswa();

        $tugasAkhirDibatalkan = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['menunggu_pembatalan', 'dibatalkan'])
            ->latest()
            ->get();

        return view('mahasiswa.tugas-akhir.crud-ta.cancel', compact('tugasAkhirDibatalkan'));
    }
}
