<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Metode ini akan menghapus kolom 'scan_at' dan 'catatan_reviewer'.
     */
    public function up(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            // Menghapus kolom yang sudah tidak digunakan lagi
            $table->dropColumn(['scan_at', 'catatan_reviewer']);
        });
    }

    /**
     * Reverse the migrations.
     * Metode ini akan mengembalikan kolom yang dihapus jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            // Menambahkan kembali kolom jika diperlukan
            $table->timestamp('scan_at')->nullable()->after('version');
            $table->text('catatan_reviewer')->nullable()->after('divalidasi_oleh');
        });
    }
};
