<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara_pra_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_id')->constrained('sidang')->onDelete('cascade');
            $table->foreignId('dicetak_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_cetak');
            $table->enum('status_dokumen', ['draft', 'dicetak', 'ditandatangani']);
            $table->string('file_path', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_pra_sidang');
    }
};
