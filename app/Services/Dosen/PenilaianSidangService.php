<?php

namespace App\Services\Dosen;

use App\Models\Dosen;
use App\Models\NilaiSidang;
use App\Models\PeranDosenTa;
use App\Models\Sidang;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class PenilaianSidangService
{
    protected Dosen $dosen;

    public function __construct()
    {
        $this->dosen = Auth::user()->dosen;
    }

    /**
     * Mengambil daftar sidang yang perlu dinilai oleh dosen yang login.
     */
    public function getSidangUntukDinilai(): \Illuminate\Database\Eloquent\Collection
    {
        // Dapatkan semua ID tugas akhir di mana dosen ini berperan sebagai penguji
        $peranPengujiTaIds = $this->dosen->peranDosenTa()
            ->where('peran', 'like', 'penguji%')
            ->pluck('tugas_akhir_id');

        // Ambil sidang dari tugas akhir tersebut yang statusnya "dijadwalkan"
        return Sidang::whereIn('tugas_akhir_id', $peranPengujiTaIds)
            ->where('status_sidang', 'dijadwalkan') // Asumsi status
            ->with(['tugasAkhir.mahasiswa.user', 'jadwalSidang.ruangan'])
            ->get();
    }

    /**
     * Menyimpan nilai sidang dari seorang penguji.
     */
    public function storeNilai(Sidang $sidang, array $validatedData): NilaiSidang
    {
        // Otorisasi sudah ditangani oleh FormRequest, tapi bisa ditambahkan pengecekan ganda
        $this->authorizeDosenIsPenguji($sidang->tugasAkhir);

        // Tambahkan ID yang diperlukan
        $validatedData['sidang_id'] = $sidang->id;
        $validatedData['dosen_id'] = $this->dosen->id;

        // Gunakan updateOrCreate untuk menghindari duplikasi nilai dari penguji yang sama
        $nilai = NilaiSidang::updateOrCreate(
            [
                'sidang_id' => $sidang->id,
                'dosen_id' => $this->dosen->id,
            ],
            $validatedData
        );

        // Setelah menyimpan, cek apakah semua penguji sudah memberi nilai
        $this->cekDanFinalisasiSidang($sidang);

        return $nilai;
    }

    /**
     * Mengecek apakah semua penguji sudah memberi nilai, jika ya, finalisasi hasilnya.
     */
    protected function cekDanFinalisasiSidang(Sidang $sidang): void
    {
        $tugasAkhir = $sidang->tugasAkhir->load('peranDosenTa');
        $jumlahPenguji = $tugasAkhir->peranDosenTa->where('peran', 'like', 'penguji%')->count();
        $jumlahNilaiMasuk = $sidang->nilaiSidang()->count();

        if ($jumlahPenguji > 0 && $jumlahNilaiMasuk >= $jumlahPenguji) {
            // Logika untuk menghitung rata-rata nilai dan menentukan kelulusan
            $totalNilai = $sidang->nilaiSidang()->sum('total_nilai'); // Asumsi ada kolom 'total_nilai'
            $rataRata = $totalNilai / $jumlahNilaiMasuk;

            // Tentukan status lulus/tidak lulus berdasarkan rata-rata
            $statusKelulusan = ($rataRata >= 56) ? 'lulus' : 'tidak_lulus'; // Contoh batas nilai

            $sidang->update([
                'status_sidang' => $statusKelulusan,
                'nilai_akhir' => $rataRata,
            ]);

            // Update juga status Tugas Akhir
            $tugasAkhir->update([
                'status' => $statusKelulusan === 'lulus' ? TugasAkhir::STATUS_LULUS_DENGAN_REVISI : TugasAkhir::STATUS_REVISI
            ]);
        }
    }

    /**
     * Helper untuk memastikan dosen adalah penguji pada sidang ini.
     */
    private function authorizeDosenIsPenguji(TugasAkhir $tugasAkhir): void
    {
        $isPenguji = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $this->dosen->id)
            ->where('peran', 'like', 'penguji%')
            ->exists();

        if (!$isPenguji) {
            throw new UnauthorizedException('Anda bukan penguji untuk sidang ini.');
        }
    }
}
