<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_id')->constrained('sidang')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->string('aspek', 100);
            $table->text('komentar')->nullable();
            $table->float('skor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_sidang');
    }
};
