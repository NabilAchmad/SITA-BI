<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusBimbinganEnumOnBimbinganTaTable extends Migration
{
    public function up(): void
    {
        // Ubah enum dengan cara raw karena Laravel belum native support enum update
        DB::statement("ALTER TABLE bimbingan_ta 
            MODIFY status_bimbingan ENUM('diajukan', 'disetujui', 'ditolak', 'selesai') NOT NULL");
    }

    public function down(): void
    {
        // Kembalikan ke enum sebelumnya
        DB::statement("ALTER TABLE bimbingan_ta 
            MODIFY status_bimbingan ENUM('diajukan', 'disetujui', 'ditolak') NOT NULL");
    }
}
