<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_judul_tugas_akhirs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('judul_tugas_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->text('judul');
            $table->integer('tahun_lulus');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('judul_tugas_akhirs');
    }
};
