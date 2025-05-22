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
<<<<<<< HEAD
            $table->enum('status_validasi', ['belum_diperiksa', 'disetujui', 'ditolak'])->default('belum_diperiksa');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->onDelete('set null');
=======
<<<<<<< HEAD
            $table->foreignId('uploaded_by')->constrained('users');
            $table->enum('status_review', ['belum_diperiksa', 'diterima', 'ditolak']);
=======
            $table->enum('status_validasi', ['belum_diperiksa', 'disetujui', 'ditolak'])->default('belum_diperiksa');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->onDelete('set null');
>>>>>>> 905ddd514a1f02e7724f6a26e444a4bf3ee356ee
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
            $table->text('catatan_reviewer')->nullable();
            $table->string('file_path');
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
