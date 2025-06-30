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
        $peranPengujiTaIds = $this->dosen->peranDosenTa()
            ->where('peran', 'like', 'penguji%')
            ->pluck('tugas_akhir_id');

        // PERBAIKAN: Menggunakan nama kolom 'status' sesuai skema DB Anda.
        return Sidang::whereIn('tugas_akhir_id', $peranPengujiTaIds)
            ->where('status', 'dijadwalkan')
            ->with(['tugasAkhir.mahasiswa.user', 'jadwalSidang.ruangan'])
            ->get();
    }

    /**
     * Menyimpan nilai sidang dari seorang penguji.
     * Logika ini sekarang menangani penyimpanan beberapa baris nilai (per aspek).
     */
    public function storeNilai(Sidang $sidang, array $validatedData): void
    {
        $this->authorizeDosenIsPenguji($sidang->tugasAkhir);

        DB::transaction(function () use ($sidang, $validatedData) {
            // Hapus nilai lama dari dosen ini untuk sidang ini, untuk menghindari duplikasi
            NilaiSidang::where('sidang_id', $sidang->id)
                ->where('dosen_id', $this->dosen->id)
                ->delete();

            // Loop melalui setiap aspek penilaian yang dikirim dari form
            foreach ($validatedData['penilaian'] as $item) {
                NilaiSidang::create([
                    'sidang_id' => $sidang->id,
                    'dosen_id'  => $this->dosen->id,
                    'aspek'     => $item['aspek'],
                    'skor'      => $item['skor'],
                    'komentar'  => $item['komentar'] ?? null,
                ]);
            }
        });

        // Setelah menyimpan, cek apakah semua penguji sudah memberi nilai
        $this->cekDanFinalisasiSidang($sidang);
    }

    /**
     * Mengecek apakah semua penguji sudah memberi nilai, jika ya, finalisasi hasilnya.
     */
    protected function cekDanFinalisasiSidang(Sidang $sidang): void
    {
        $tugasAkhir = $sidang->tugasAkhir->load('peranDosenTa');
        $jumlahPenguji = $tugasAkhir->peranDosenTa->where('peran', 'like', 'penguji%')->count();

        // Dapatkan semua ID dosen penguji
        $pengujiIds = $tugasAkhir->peranDosenTa->where('peran', 'like', 'penguji%')->pluck('dosen_id');

        // Hitung berapa banyak penguji yang sudah submit nilai
        $jumlahNilaiMasuk = NilaiSidang::where('sidang_id', $sidang->id)
            ->whereIn('dosen_id', $pengujiIds)
            ->distinct('dosen_id')
            ->count();

        if ($jumlahPenguji > 0 && $jumlahNilaiMasuk >= $jumlahPenguji) {
            // Logika untuk menghitung rata-rata nilai
            $totalSkor = NilaiSidang::where('sidang_id', $sidang->id)->sum('skor');
            $jumlahAspek = NilaiSidang::where('sidang_id', $sidang->id)->count();
            $rataRata = ($jumlahAspek > 0) ? $totalSkor / $jumlahAspek : 0;

            // Tentukan status lulus/tidak lulus berdasarkan rata-rata
            $statusKelulusan = ($rataRata >= 56) ? 'lulus' : 'tidak_lulus'; // Contoh batas nilai

            // PERBAIKAN: Menggunakan nama kolom 'status' sesuai skema DB
            $sidang->update(['status' => $statusKelulusan]);
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
