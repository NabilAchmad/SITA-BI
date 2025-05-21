<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('tanggal_pengajuan'); // Menambah kolom file_path
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->dropColumn('file_path'); // Hapus kolom jika rollback
        });
    }
};
