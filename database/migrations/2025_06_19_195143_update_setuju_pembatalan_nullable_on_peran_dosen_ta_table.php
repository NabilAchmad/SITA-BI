<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateSetujuPembatalanNullableOnPeranDosenTaTable extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peran_dosen_ta 
            MODIFY COLUMN setuju_pembatalan ENUM('ya', 'tidak') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peran_dosen_ta 
            MODIFY COLUMN setuju_pembatalan ENUM('ya', 'tidak') NOT NULL DEFAULT 'tidak'");
    }
}
