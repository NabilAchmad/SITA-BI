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
        Schema::create('revisi_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir');
            $table->foreignId('user_id')->constrained('dosen');
            $table->text('catatan');
            $table->enum('status_revisi', ['diperlukan', 'tidak perlu']);
            $table->unsignedBigInteger('file_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisi_t_a_s');
    }
};
