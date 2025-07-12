<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            // Mengubah definisi ENUM untuk menambahkan 'dibatalkan'
            $table->enum('status_bimbingan', [
                'diajukan',
                'dijadwalkan',
                'disetujui',
                'ditolak',
                'selesai',
                'dibatalkan' // <-- Status baru ditambahkan
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            // Mengembalikan ke kondisi semula jika di-rollback
            $table->enum('status_bimbingan', [
                'diajukan',
                'disetujui',
                'ditolak',
                'selesai'
            ])->change();
        });
    }
};
