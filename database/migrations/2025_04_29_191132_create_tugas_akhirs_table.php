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
                'diajukan',       // Judul baru diajukan
                'draft',          // Dalam proses bimbingan
                'revisi',         // Butuh revisi (terkait tabel `revisi_ta`)
                'disetujui',      // Judul/dokumen disetujui tanpa revisi
                'lulus_tanpa_revisi',
                'lulus_dengan_revisi',
                'ditolak'         // Ditolak Kaprodi/dosen
            ])->default('diajukan');
            $table->date('tanggal_pengajuan');
            // Kolom baru untuk plagiasi dan tracking
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
