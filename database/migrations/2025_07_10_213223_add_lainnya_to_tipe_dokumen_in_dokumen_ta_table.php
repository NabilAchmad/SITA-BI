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
        // Perintah ini akan mengubah kolom 'tipe_dokumen' untuk menambahkan 'lainnya'
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->enum('tipe_dokumen', [
                'proposal',
                'draft',
                'final',
                'lainnya' // Menambahkan nilai baru
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perintah ini akan mengembalikan kolom ke kondisi semula jika migrasi di-rollback
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->enum('tipe_dokumen', [
                'proposal',
                'draft',
                'final'
            ])->change();
        });
    }
};
