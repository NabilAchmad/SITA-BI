<?php

namespace App\Services\Kaprodi;

use App\Models\JudulTugasAkhir;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ValidasiService
{
    // File: app/Services/Kaprodi/ValidasiService.php

    public function getValidationLists(): array
    {
        $user = Auth::user();
        $prodi = null; // 1. Inisialisasi variabel prodi

        // 2. LOGIKA BARU: Dapatkan prodi dari role user yang login
        if ($user && $user->roles) { // Pastikan user ada dan punya relasi roles
            foreach ($user->roles as $role) {
                // Cek jika nama role mengandung 'kaprodi-'
                if (str_contains($role->nama_role, 'kaprodi-')) {
                    // Ambil kode prodinya (misal: 'd3' dari 'kaprodi-d3')
                    $prodiCode = str_replace('kaprodi-', '', $role->nama_role);
                    // Ubah menjadi format yang disimpan di tabel mahasiswa (misal: 'D3')
                    $prodi = strtoupper($prodiCode);
                    break; // Hentikan pencarian jika role kaprodi sudah ditemukan
                }
            }
        }

        // 3. Jika prodi tidak ditemukan (user bukan kaprodi), kembalikan koleksi kosong
        if (!$prodi) {
            return [
                'tugasAkhirMenunggu' => collect(),
                'tugasAkhirDiterima' => collect(),
                'tugasAkhirDitolak' => collect(),
            ];
        }

        // PERBAIKAN: Menggunakan status 'diajukan' sesuai skema DB Anda
        $statusMenunggu = 'diajukan';
        $statusDiterima = 'disetujui';
        $statusDitolak = 'ditolak';

        // 4. Query akan berjalan dengan benar menggunakan prodi yang didapat dari role
        $baseQuery = TugasAkhir::query()
            ->with(['mahasiswa.user'])
            ->whereHas('mahasiswa', fn($q) => $q->where('prodi', $prodi)); // <-- $prodi sekarang sudah benar

        // ... sisa kode Anda tetap sama ...
        if ($search = request('search')) {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")->orWhereHas('mahasiswa', function ($subQuery) use ($search) {

                    $subQuery->where('nim', 'like', "%{$search}%")

                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
                });
            });
        }

        $allTugasAkhir = $baseQuery->latest('tanggal_pengajuan')->get();

        return [
            'tugasAkhirMenunggu' => (clone $baseQuery)->where('status', $statusMenunggu)->latest('tanggal_pengajuan')->get(),
            'tugasAkhirDiterima' => (clone $baseQuery)->where('status', $statusDiterima)->latest('tanggal_pengajuan')->get(),
            'tugasAkhirDitolak'  => (clone $baseQuery)->where('status', 'ditolak')->latest('tanggal_pengajuan')->get(),
        ];
    }

    public function getValidationDetails(TugasAkhir $tugasAkhir): array
    {
        // PERBAIKAN: Menggunakan relasi yang benar (approver/rejector)
        return [
            'nama' => $tugasAkhir->mahasiswa?->user?->name ?? 'Data Mahasiswa Tidak Ditemukan',
            'nim' => $tugasAkhir->mahasiswa?->nim ?? '-',
            'prodi' => $tugasAkhir->mahasiswa?->prodi ? 'd' . strtoupper($tugasAkhir->mahasiswa->prodi) . ' Bahasa Inggris' : 'N/A',
            'judul' => $tugasAkhir->judul,
            'actionable' => $tugasAkhir->status === 'diajukan', // Status yang bisa divalidasi
            'disetujui_oleh' => $tugasAkhir->status === 'disetujui' ? ($tugasAkhir->disetujui_oleh?->name ?? 'N/A') : null,
            'tanggal_disetujui' => $tugasAkhir->status === 'disetujui' ? Carbon::parse($tugasAkhir->updated_at)->translatedFormat('d F Y \p\u\k\u\l H:i') : null,
            'ditolak_oleh' => $tugasAkhir->status === 'ditolak' ? ($tugasAkhir->ditolak_oleh?->name ?? 'N/A') : null,
            'tanggal_ditolak' => $tugasAkhir->status === 'ditolak' ? Carbon::parse($tugasAkhir->updated_at)->translatedFormat('d F Y \p\u\k\u\l H:i') : null,
            'alasan_penolakan' => $tugasAkhir->status === 'ditolak' ? $tugasAkhir->alasan_penolakan : null,
        ];
    }

    public function approveTugasAkhir(TugasAkhir $tugasAkhir): void
    {
        // PERBAIKAN: Menggunakan nama kolom yang benar dari skema DB
        $tugasAkhir->update([
            'status' => 'disetujui',
            'disetujui_oleh' => Auth::id(),
            'alasan_penolakan' => null,
            'ditolak_oleh' => null,
        ]);
    }

    public function rejectTugasAkhir(TugasAkhir $tugasAkhir, string $alasan): void
    {
        // PERBAIKAN: Menggunakan nama kolom yang benar dari skema DB
        $tugasAkhir->update([
            'status' => 'ditolak',
            'ditolak_oleh' => Auth::id(),
            'alasan_penolakan' => $alasan,
            'disetujui_oleh' => null,
        ]);
    }

    public function cekKemiripanJudulCerdas(TugasAkhir $tugasAkhir): array
    {
        $judulBaruNormal = $this->bersihkanTeks($tugasAkhir->judul);
        $ambangBatas = 70;
        $hasilMirip = [];

        JudulTugasAkhir::query()
            ->select(['judul', 'nama_mahasiswa', 'tahun_lulus'])
            ->chunk(200, function ($arsipJudul) use ($judulBaruNormal, $ambangBatas, &$hasilMirip) {
                foreach ($arsipJudul as $item) {
                    $judulLamaNormal = $this->bersihkanTeks($item->judul);
                    if (empty($judulLamaNormal)) continue;
                    similar_text($judulBaruNormal, $judulLamaNormal, $persentase);
                    if ($persentase >= $ambangBatas) {
                        $hasilMirip[] = [
                            'judul' => $item->judul,
                            'nama_mahasiswa' => $item->nama_mahasiswa,
                            'tahun_lulus' => $item->tahun_lulus,
                            'persentase' => round($persentase, 2)
                        ];
                    }
                }
            });

        if (!empty($hasilMirip)) {
            usort($hasilMirip, fn($a, $b) => $b['persentase'] <=> $a['persentase']);
        }

        return $hasilMirip;
    }

    private function bersihkanTeks(string $teks): string
    {
        $teks = strtolower($teks);
        $teks = preg_replace('/[^\p{L}\p{N}\s]/u', '', $teks);
        $stopwords = [
            'di',
            'dan',
            'yang',
            'untuk',
            'pada',
            'ke',
            'dengan',
            'sebagai',
            'dalam',
            'ini',
            'analisis',
            'perancangan',
            'sistem',
            'informasi',
            'implementasi',
            'studi',
            'kasus',
            'pengaruh',
            'terhadap',
            'sebuah',
            'the',
            'of',
            'on',
            'an',
            'a',
            'in',
            'to',
            'for'
        ];
        $kata = explode(' ', $teks);
        $kataPenting = array_diff($kata, $stopwords);
        return preg_replace('/\s+/', ' ', trim(implode(' ', $kataPenting)));
    }
}
