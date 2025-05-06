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
        Schema::create('review_dokumen_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_ta_id')->constrained('dokumen_ta');
            $table->foreignId('reviewer_id')->constrained('dosen');
            $table->enum('status_review', ['belum_diperiksa', 'diterima', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_dokumen_t_a_s');
    }
};
