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
        Schema::create('dokumen_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir');
            $table->enum('tipe_dokumen', ['proposal', 'draft', 'final']);
            $table->enum('status_validasi', ['belum_diperiksa', 'disetujui', 'ditolak'])->default('belum_diperiksa');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_reviewer')->nullable();
            $table->string('file_path');

            // Hapus penggunaan after() karena tidak diperlukan pada create table
            $table->integer('version')->default(1);
            $table->timestamp('scan_at')->nullable()->comment('Terakhir dipindai plagiasi');
            $table->foreignId('scanned_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_t_a_s');
    }
};
