<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInSidangTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'menunggu_verifikasi' to the status enum
        DB::statement("ALTER TABLE sidang MODIFY COLUMN status ENUM('menunggu', 'menunggu_verifikasi', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus') NOT NULL DEFAULT 'menunggu_verifikasi'");
        DB::statement("ALTER TABLE sidang MODIFY COLUMN jenis_sidang ENUM('proposal', 'akhir') NOT NULL DEFAULT 'akhir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'menunggu_verifikasi' from the status enum
        DB::statement("ALTER TABLE sidang MODIFY COLUMN status ENUM('menunggu', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus') NOT NULL DEFAULT 'menunggu'");
    }
}
