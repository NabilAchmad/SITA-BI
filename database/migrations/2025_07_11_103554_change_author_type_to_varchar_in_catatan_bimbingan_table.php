<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Perintah ini akan mengubah tipe kolom menjadi VARCHAR
        Schema::table('catatan_bimbingan', function (Blueprint $table) {
            $table->string('author_type')->change();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        // Perintah untuk mengembalikan ke ENUM jika migrasi di-rollback
        Schema::table('catatan_bimbingan', function (Blueprint $table) {
            $table->enum('author_type', ['mahasiswa', 'dosen'])->change();
        });
    }
};
