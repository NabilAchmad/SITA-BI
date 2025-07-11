<?php

namespace App\Services\Kaprodi;

use App\Models\JudulTugasAkhir;
use App\Models\TugasAkhir;
use App\Services\PorterStemmer; // Pastikan path ini benar
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Opsional, untuk mencatat log error

class ValidasiService
{
    /**
     * ✅ PERBAIKAN: Mengambil daftar TA untuk halaman validasi, difilter berdasarkan peran.
     * Logika ini sekarang menangani semua peran staf (Admin, Kajur, Kaprodi, Dosen) dengan benar.
     */
    /**
     * ✅ PERBAIKAN: Mengambil daftar TA untuk halaman validasi, difilter berdasarkan peran.
     * Metode ini tidak lagi memerlukan parameter Request dan menggunakan helper global.
     */
    public function getValidationLists(): array
    {
        $user = Auth::user();

        // Mulai dengan query dasar yang memuat relasi yang dibutuhkan.
        $baseQuery = TugasAkhir::query()->with(['mahasiswa.user']);

        // Terapkan filter prodi HANYA untuk Kaprodi.
        // Kajur, Admin, dan Dosen Biasa akan melewati kondisi ini dan melihat semua prodi.
        if ($user->hasRole('kaprodi-d4')) {
            $baseQuery->whereHas('mahasiswa', fn($q) => $q->where('prodi', 'd4'));
        } elseif ($user->hasRole('kaprodi-d3')) {
            $baseQuery->whereHas('mahasiswa', fn($q) => $q->where('prodi', 'd3'));
        }

        // Terapkan filter pencarian jika ada, menggunakan helper global 'request()'.
        if ($search = request('search')) {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($subQuery) use ($search) {
                        $subQuery->where('nim', 'like', "%{$search}%")
                            ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
                    });
            });
        }

        // Ambil semua data yang relevan dalam satu query.
        $allTugasAkhir = $baseQuery->latest('tanggal_pengajuan')->get();

        // Pisahkan data berdasarkan status menggunakan metode collection yang efisien.
        return [
            'tugasAkhirMenunggu' => $allTugasAkhir->where('status', TugasAkhir::STATUS_DIAJUKAN),
            'tugasAkhirDiterima' => $allTugasAkhir->where('status', TugasAkhir::STATUS_DISETUJUI),
            'tugasAkhirDitolak'  => $allTugasAkhir->whereIn('status', [TugasAkhir::STATUS_DITOLAK, TugasAkhir::STATUS_REVISI]),
        ];
    }

    /**
     * Mengambil detail untuk ditampilkan di modal.
     * Logika 'actionable' telah dihapus dari sini. Tanggung jawab ini sekarang
     * berada di Controller yang akan memanggil Policy.
     */
    public function getValidationDetails(TugasAkhir $tugasAkhir): array
    {
        return [
            'nama' => $tugasAkhir->mahasiswa?->user?->name ?? 'Data Mahasiswa Tidak Ditemukan',
            'nim' => $tugasAkhir->mahasiswa?->nim ?? '-',
            'prodi' => $tugasAkhir->mahasiswa?->prodi ? strtoupper($tugasAkhir->mahasiswa->prodi) . ' Bahasa Inggris' : 'N/A',
            'judul' => $tugasAkhir->judul,
            'actionable' => $tugasAkhir->status === 'diajukan' && auth()->user()->hasAnyRole(['kaprodi-d3', 'kaprodi-d4']),

            // ✅ PERBAIKAN: Panggil relasi 'disetujui_oleh' dan 'ditolak_oleh'.
            'disetujui_oleh' => $tugasAkhir->status === 'disetujui' ? ($tugasAkhir->disetujui_oleh?->name ?? 'N/A') : null,
            'tanggal_disetujui' => $tugasAkhir->status === 'disetujui' ? Carbon::parse($tugasAkhir->updated_at)->translatedFormat('d F Y \p\u\k\u\l H:i') : null,
            'ditolak_oleh' => $tugasAkhir->status === 'ditolak' ? ($tugasAkhir->ditolak_oleh?->name ?? 'N/A') : null,
            'tanggal_ditolak' => $tugasAkhir->status === 'ditolak' ? Carbon::parse($tugasAkhir->updated_at)->translatedFormat('d F Y \p\u\k\u\l H:i') : null,
            'alasan_penolakan' => $tugasAkhir->status === 'ditolak' ? $tugasAkhir->alasan_penolakan : null,
        ];
    }

    /**
     * Menyetujui pengajuan tugas akhir.
     */
    public function approveTugasAkhir(TugasAkhir $tugasAkhir): void
    {
        $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_DISETUJUI,
            'disetujui_oleh' => Auth::id(),
            'alasan_penolakan' => null,
            'ditolak_oleh' => null,
        ]);
    }

    /**
     * Menolak pengajuan tugas akhir.
     */
    public function rejectTugasAkhir(TugasAkhir $tugasAkhir, string $alasan): void
    {
        $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_DITOLAK,
            'ditolak_oleh' => Auth::id(),
            'alasan_penolakan' => $alasan,
            'disetujui_oleh' => null,
        ]);
    }

    // ====================================================================
    // BAGIAN PENGECEKAN KEMIRIPAN JUDUL DENGAN AI (HIBRIDA) - VERSI FINAL
    // ====================================================================

    /**
     * @var string Alamat API Python yang sedang berjalan.
     */
    private string $similarityApiUrl = 'http://127.0.0.1:5000/similarity';

    /**
     * [VERSI EFISIEN] Memeriksa kemiripan dengan satu panggilan API.
     *
     * @param TugasAkhir $tugasAkhir Judul baru yang akan dicek.
     * @return array Hasil judul yang mirip, langsung dari AI Service.
     */
    public function cekKemiripanJudulCerdas(TugasAkhir $tugasAkhir): array
    {
        // 1. Siapkan payload sederhana untuk dikirim ke Python.
        $payload = [
            'judul' => $tugasAkhir->judul,
            'top_n' => 10
        ];

        // 2. Lakukan SATU KALI panggilan ke AI Service.
        try {
            // Pastikan URL ini menunjuk ke endpoint Python yang benar.
            $response = Http::timeout(20)->post('http://127.0.0.1:5000/find_similar', $payload);

            if (!$response->successful()) {
                Log::error('AI Service request failed: ' . $response->body());
                return [];
            }

            $hasilDariAi = $response->json();
            if (empty($hasilDariAi)) {
                return [];
            }

            // 3. Ambil detail (nama & tahun) dari database LOKAL secara efisien.
            $similarIds = array_column($hasilDariAi, 'id');
            $detailJudul = JudulTugasAkhir::whereIn('id', $similarIds)
                ->get(['id', 'nama_mahasiswa', 'tahun_lulus'])
                ->keyBy('id');

            // 4. Gabungkan data dari AI dan database lokal untuk ditampilkan.
            $hasilMirip = [];
            foreach ($hasilDariAi as $item) {
                $detail = $detailJudul->get($item['id']);

                $hasilMirip[] = [
                    'judul'          => $item['judul'],
                    'nama_mahasiswa' => $detail->nama_mahasiswa ?? 'N/A',
                    'tahun_lulus'    => $detail->tahun_lulus ?? 'N/A',
                    'persentase'     => round($item['similarity_score'] * 100, 2)
                ];
            }

            return $hasilMirip;
        } catch (\Exception $e) {
            Log::error('AI Service connection error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper untuk memecah teks menjadi N-grams.
     */
    private function getNgrams(string $text, int $n): array
    {
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $ngrams = [];
        $wordCount = count($words);
        if ($wordCount < $n) {
            if ($wordCount > 0) $ngrams[] = implode(' ', $words);
            return $ngrams;
        }
        for ($i = 0; $i <= $wordCount - $n; $i++) {
            $ngrams[] = implode(' ', array_slice($words, $i, $n));
        }
        return array_unique($ngrams);
    }

    /**
     * Membersihkan teks untuk perbandingan leksikal (dengan stemming).
     */
    private function bersihkanTeks(string $teks): string
    {
        $teks = strtolower($teks);
        $teks = preg_replace('/[^a-z\s]/', '', $teks);

        $stopwords = [
            'a',
            'about',
            'above',
            'after',
            'again',
            'against',
            'all',
            'am',
            'an',
            'and',
            'any',
            'are',
            'as',
            'at',
            'be',
            'because',
            'been',
            'before',
            'being',
            'below',
            'between',
            'both',
            'but',
            'by',
            'can',
            'did',
            'do',
            'does',
            'doing',
            'don',
            'down',
            'during',
            'each',
            'few',
            'for',
            'from',
            'further',
            'had',
            'has',
            'have',
            'having',
            'he',
            'her',
            'here',
            'hers',
            'herself',
            'him',
            'himself',
            'his',
            'how',
            'i',
            'if',
            'in',
            'into',
            'is',
            'it',
            'its',
            'itself',
            'just',
            'me',
            'more',
            'most',
            'my',
            'myself',
            'no',
            'nor',
            'not',
            'now',
            'o',
            'of',
            'off',
            'on',
            'once',
            'only',
            'or',
            'other',
            'our',
            'ours',
            'ourselves',
            'out',
            'over',
            'own',
            's',
            'same',
            'she',
            'should',
            'so',
            'some',
            'such',
            't',
            'than',
            'that',
            'the',
            'their',
            'theirs',
            'them',
            'themselves',
            'then',
            'there',
            'these',
            'they',
            'this',
            'those',
            'through',
            'to',
            'too',
            'under',
            'until',
            'up',
            'very',
            'was',
            'we',
            'were',
            'what',
            'when',
            'where',
            'which',
            'while',
            'who',
            'whom',
            'why',
            'will',
            'with',
            'you',
            'your',
            'yours',
            'yourself',
            'yourselves',
            'analysis',
            'design',
            'system',
            'information',
            'implementation',
            'study',
            'case',
            'effect',
            'influence'
        ];

        $pattern = '/\b(' . implode('|', $stopwords) . ')\b/i';
        $teks = preg_replace($pattern, '', $teks);

        $kataPenting = explode(' ', $teks);
        $kataDasar = [];
        foreach ($kataPenting as $kata) {
            if (!empty($kata)) {
                $kataDasar[] = PorterStemmer::stem($kata);
            }
        }

        return preg_replace('/\s+/', ' ', trim(implode(' ', $kataDasar)));
    }
}
