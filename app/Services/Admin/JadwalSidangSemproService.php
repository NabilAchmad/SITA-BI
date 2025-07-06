<?php

namespace App\Services\Admin;

use App\Models\JadwalSidang;
use App\Models\Sidang;
use Illuminate\Support\Facades\DB;
use App\Models\TugasAkhir; // Pastikan untuk mengimpor model TugasAkhir
use Exception;

class JadwalSidangSemproService
{
    /**
     * Logika untuk membuat jadwal sidang baru.
     */
    public function schedule(array $validatedData): JadwalSidang
    {
        $this->checkScheduleConflict($validatedData);

        return DB::transaction(function () use ($validatedData) {
            $sidang = Sidang::findOrFail($validatedData['sidang_id']);

            // Nonaktifkan sidang lama yang mungkin aktif
            Sidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $jadwal = JadwalSidang::create($validatedData);

            $sidang->update([
                'status' => 'dijadwalkan',
                'is_active' => true,
            ]);

            // Tambahkan logika untuk menyimpan dosen penguji di sini jika ada

            return $jadwal;
        });
    }

    /**
     * Logika untuk menandai status sidang (lulus/tidak).
     */
    public function markAsFinished(Sidang $sidang, string $status): Sidang
    {
        // 1. Validasi status yang sudah final (logika lama, tetap bagus untuk ada)
        if (in_array($sidang->status, ['lulus', 'lulus_dengan_revisi', 'tidak_lulus'])) {
            throw new Exception('Sidang ini sudah memiliki status final sebelumnya.');
        }

        // 2. Memperbarui status pada record sidang itu sendiri
        $sidang->status = $status;
        if ($status === 'tidak_lulus') {
            $sidang->is_active = false;
        }
        $sidang->save();

        // 3. [LOGIKA BARU] Memperbarui status Tugas Akhir jika sidang dinyatakan lulus
        // Hanya jalankan jika statusnya adalah 'lulus' atau 'lulus_dengan_revisi'
        if ($status === 'lulus' || $status === 'lulus_dengan_revisi') {
            // Mengambil model TugasAkhir yang terhubung melalui relasi
            $tugasAkhir = $sidang->tugasAkhir;

            // Pastikan relasi tugasAkhir ada sebelum mencoba mengubah statusnya
            if ($tugasAkhir) {
                $tugasAkhir->status = 'disetujui'; // Mengubah status Tugas Akhir
                $tugasAkhir->save(); // Menyimpan perubahan pada Tugas Akhir
            }
        }

        return $sidang;
    }

    /**
     * Logika untuk memeriksa jadwal yang bentrok.
     * @throws \Exception
     */
    protected function checkScheduleConflict(array $data)
    {
        $jadwalBentrok = JadwalSidang::where('ruangan_id', $data['ruangan_id'])
            ->where('tanggal', $data['tanggal'])
            ->where(function ($query) use ($data) {
                $query->whereTime('waktu_selesai', '>', $data['waktu_mulai'])
                    ->whereTime('waktu_mulai', '<', $data['waktu_selesai']);
            })->exists();

        if ($jadwalBentrok) {
            throw new Exception('Ruangan sudah terpakai pada rentang waktu tersebut.');
        }
    }
}
