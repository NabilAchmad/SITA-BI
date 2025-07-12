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
        Schema::table('dokumen_ta', function (Blueprint $table) {
            // Menambahkan kolom baru untuk menyimpan nama asli file setelah kolom 'file_path'
            $table->string('nama_file_asli')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->dropColumn('nama_file_asli');
        });
    }
};
