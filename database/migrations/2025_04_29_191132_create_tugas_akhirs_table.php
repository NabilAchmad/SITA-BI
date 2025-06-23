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
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('judul', 255);
            $table->text('abstrak');


            $table->enum('status', [
                'diajukan',
                'draft',
                'revisi',
                'disetujui',
                'lulus_tanpa_revisi',
                'lulus_dengan_revisi',
                'ditolak',
                'menunggu_pembatalan', // ← NEW
                'dibatalkan'           // ← NEW
                // 'ditolak',
                // 'menunggu_pembatalan', // ← NEW
                // 'dibatalkan' ,          // ← NEW
            ])->default('diajukan');
            $table->string('approved_by')->nullable();

            $table->date('tanggal_pengajuan');

            // Plagiarisme
            // Plagiarisme
            $table->float('similarity_score')->nullable()->comment('Skor kemiripan judul/abstrak (0-100%)');
            $table->text('alasan_penolakan')->nullable()->comment('Catatan jika status=ditolak');
            $table->timestamp('terakhir_dicek')->nullable()->comment('Terakhir cek plagiasi');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhirs');
    }
};
