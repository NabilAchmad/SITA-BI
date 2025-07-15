<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE dokumen_ta MODIFY COLUMN tipe_dokumen ENUM('proposal', 'draft', 'final', 'lainnya', 'bimbingan') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE dokumen_ta MODIFY COLUMN tipe_dokumen ENUM('proposal', 'draft', 'final', 'lainnya') NOT NULL");
    }
};
