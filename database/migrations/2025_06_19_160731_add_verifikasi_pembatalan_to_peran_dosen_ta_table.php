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
        Schema::table('peran_dosen_ta', function (Blueprint $table) {
            $table->enum('setuju_pembatalan', ['ya', 'tidak', 'belum'])
                ->default('belum')
                ->after('peran')
                ->comment('Persetujuan dosen terhadap pembatalan TA');

            $table->timestamp('tanggal_verifikasi')
                ->nullable()
                ->after('setuju_pembatalan')
                ->comment('Waktu dosen memberikan keputusan');

            $table->text('catatan_verifikasi')
                ->nullable()
                ->after('tanggal_verifikasi')
                ->comment('Catatan dari dosen saat verifikasi pembatalan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peran_dosen_ta', function (Blueprint $table) {
            //
        });
    }
};
