<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->unsignedBigInteger('tawaran_topik_id')->nullable()->after('mahasiswa_id');
            $table->foreign('tawaran_topik_id')->references('id')->on('tawaran_topik')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            //
        });
    }
};
