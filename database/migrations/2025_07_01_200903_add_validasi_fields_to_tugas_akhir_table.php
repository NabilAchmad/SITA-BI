<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ditolak_oleh')->nullable()->constrained('users')->nullOnDelete();
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
