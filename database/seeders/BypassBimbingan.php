<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BypassBimbingan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Data bimbingan yang sudah ada (2 row)
        $existingSessions = 2;

        // Data untuk 8 bimbingan tambahan
        $additionalSessions = 8;

        // Dosen pembimbing
        $dosenPembimbing1 = 1;
        $dosenPembimbing2 = 2;

        // Tanggal awal untuk bimbingan
        $startDate = Carbon::create(2025, 7, 1);

        for ($i = 1; $i <= $additionalSessions; $i++) {
            $sessionNumber = $existingSessions + $i;
            $bimbinganDate = $startDate->addDays(rand(3, 7))->format('Y-m-d');
            $jamBimbingan = rand(8, 16) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':00';

            // Bimbingan dengan pembimbing 1
            DB::table('bimbingan_ta')->insert([
                'tugas_akhir_id' => 1,
                'dosen_id' => $dosenPembimbing1,
                'peran' => 'pembimbing1',
                'sesi_ke' => $sessionNumber,
                'tanggal_bimbingan' => $bimbinganDate,
                'jam_bimbingan' => $jamBimbingan,
                'status_bimbingan' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Bimbingan dengan pembimbing 2 (untuk sesi yang sama)
            DB::table('bimbingan_ta')->insert([
                'tugas_akhir_id' => 1,
                'dosen_id' => $dosenPembimbing2,
                'peran' => 'pembimbing2',
                'sesi_ke' => $sessionNumber,
                'tanggal_bimbingan' => $bimbinganDate,
                'jam_bimbingan' => $jamBimbingan,
                'status_bimbingan' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
