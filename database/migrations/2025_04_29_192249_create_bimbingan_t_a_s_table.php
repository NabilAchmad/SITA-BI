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
        Schema::create('bimbingan_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir');
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->date('tanggal_bimbingan');
            $table->text('catatan');
            $table->enum('status_bimbingan', ['diajukan', 'disetujui', 'ditolak']);
            $table->foreignId('file_id')->constrained('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_t_a_s');
    }
};
