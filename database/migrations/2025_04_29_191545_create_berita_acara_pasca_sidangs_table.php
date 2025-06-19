<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara_pasca_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_id')->constrained('sidang')->onDelete('cascade');
            $table->text('kesimpulan');
            $table->enum('hasil_sidang', ['lulus', 'tidak lulus']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_pasca_sidang');
    }
};
