<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan data dosen yang sedang login
use App\Models\Sidang; // Asumsi model Sidang sudah ada
use App\Models\Mahasiswa; // Asumsi model Mahasiswa sudah ada
use App\Models\Dosen; // Asumsi model Dosen sudah ada

class PengujiController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa yang diuji oleh dosen penguji yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan ID dosen yang sedang login
        // Asumsi bahwa dosen yang login memiliki ID yang dapat diakses melalui Auth::user()->id
        // atau Auth::user()->dosen_id jika ada relasi khusus.
        // Untuk contoh ini, kita asumsikan Auth::user()->id adalah ID dosen.
        $dosenId = Auth::user()->id; // Ganti dengan cara yang sesuai untuk mendapatkan ID dosen

        // Mendapatkan daftar sidang di mana dosen ini adalah salah satu penguji
        $daftarSidang = Sidang::where('dosen_penguji1_id', $dosenId)
            ->orWhere('dosen_penguji2_id', $dosenId)
            ->orWhere('dosen_penguji3_id', $dosenId)
            ->orWhere('dosen_penguji4_id', $dosenId)
            ->with('mahasiswa') // Memuat relasi mahasiswa untuk mendapatkan detail mahasiswa
            ->get();

        // Mengirim data ke view
        return view('dosen.penguji.index', compact('daftarSidang'));
    }

    /**
     * Tampilkan detail jadwal sidang untuk mahasiswa tertentu yang diuji.
     *
     * @param  int  $sidangId ID Sidang
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showJadwal($sidangId)
    {
        $dosenId = Auth::user()->id; // Ganti dengan cara yang sesuai untuk mendapatkan ID dosen

        // Cari sidang berdasarkan ID dan pastikan dosen yang login adalah salah satu penguji
        $sidang = Sidang::where('id', $sidangId)
            ->where(function ($query) use ($dosenId) {
                $query->where('dosen_penguji1_id', $dosenId)
                    ->orWhere('dosen_penguji2_id', $dosenId)
                    ->orWhere('dosen_penguji3_id', $dosenId)
                    ->orWhere('dosen_penguji4_id', $dosenId);
            })
            ->with('mahasiswa') // Memuat relasi mahasiswa
            ->first();

        // Jika sidang tidak ditemukan atau dosen bukan penguji untuk sidang ini
        if (!$sidang) {
            return redirect()->back()->with('error', 'Sidang tidak ditemukan atau Anda tidak berhak mengakses.');
        }

        // Mengirim data ke view
        return view('dosen.penguji.show_jadwal', compact('sidang'));
    }

    /**
     * Tangani pengisian nilai sidang oleh dosen penguji.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $sidangId ID Sidang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inputNilai(Request $request, $sidangId)
    {
        $dosenId = Auth::user()->id; // Ganti dengan cara yang sesuai untuk mendapatkan ID dosen

        // Validasi input nilai
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100', // Nilai antara 0 dan 100
        ]);

        // Cari sidang berdasarkan ID dan pastikan dosen yang login adalah salah satu penguji
        $sidang = Sidang::where('id', $sidangId)
            ->where(function ($query) use ($dosenId) {
                $query->where('dosen_penguji1_id', $dosenId)
                    ->orWhere('dosen_penguji2_id', $dosenId)
                    ->orWhere('dosen_penguji3_id', $dosenId)
                    ->orWhere('dosen_penguji4_id', $dosenId);
            })
            ->first();

        // Jika sidang tidak ditemukan atau dosen bukan penguji untuk sidang ini
        if (!$sidang) {
            return redirect()->back()->with('error', 'Sidang tidak ditemukan atau Anda tidak berhak mengisi nilai.');
        }

        // Tentukan kolom nilai mana yang harus diisi berdasarkan ID dosen yang login
        $nilaiKolom = null;
        if ($sidang->dosen_penguji1_id == $dosenId) {
            $nilaiKolom = 'nilai_dosen_penguji1';
        } elseif ($sidang->dosen_penguji2_id == $dosenId) {
            $nilaiKolom = 'nilai_dosen_penguji2';
        } elseif ($sidang->dosen_penguji3_id == $dosenId) {
            $nilaiKolom = 'nilai_dosen_penguji3';
        } elseif ($sidang->dosen_penguji4_id == $dosenId) {
            $nilaiKolom = 'nilai_dosen_penguji4';
        }

        // Jika kolom nilai tidak ditemukan (seharusnya tidak terjadi jika query di atas benar)
        if (is_null($nilaiKolom)) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai penguji untuk sidang ini.');
        }

        // Simpan nilai ke kolom yang sesuai
        $sidang->$nilaiKolom = $request->input('nilai');
        $sidang->save();

        // Setelah nilai disimpan, cek apakah semua nilai penguji sudah terisi
        // Jika semua nilai sudah terisi, lakukan perhitungan dan update status
        if (
            !is_null($sidang->nilai_dosen_penguji1) &&
            !is_null($sidang->nilai_dosen_penguji2) &&
            !is_null($sidang->nilai_dosen_penguji3) &&
            !is_null($sidang->nilai_dosen_penguji4)
        ) {

            $this->calculateAndSetStatus($sidang);
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Lakukan perhitungan nilai rata-rata dan perbarui status hasil sidang.
     * Metode ini akan dipanggil secara otomatis setelah semua 4 nilai penguji terisi.
     *
     * @param  \App\Models\Sidang  $sidang
     * @return void
     */
    protected function calculateAndSetStatus(Sidang $sidang)
    {
        // Pastikan semua nilai penguji sudah ada sebelum perhitungan
        if (
            is_null($sidang->nilai_dosen_penguji1) ||
            is_null($sidang->nilai_dosen_penguji2) ||
            is_null($sidang->nilai_dosen_penguji3) ||
            is_null($sidang->nilai_dosen_penguji4)
        ) {
            // Seharusnya tidak terjadi jika dipanggil setelah semua nilai terisi
            return;
        }

        // Perhitungan nilai rata-rata (masing-masing 25%)
        $nilaiRataRata = (
            ($sidang->nilai_dosen_penguji1 * 0.25) +
            ($sidang->nilai_dosen_penguji2 * 0.25) +
            ($sidang->nilai_dosen_penguji3 * 0.25) +
            ($sidang->nilai_dosen_penguji4 * 0.25)
        );

        // Simpan nilai rata-rata ke database
        $sidang->nilai_rata_rata = $nilaiRataRata;

        // Tentukan status hasil
        if ($nilaiRataRata >= 60) {
            $sidang->status_hasil = 'lulus_revisi';
        } else {
            $sidang->status_hasil = 'tidak_lulus'; // Atau status lain yang sesuai
        }

        $sidang->save();
    }
}
