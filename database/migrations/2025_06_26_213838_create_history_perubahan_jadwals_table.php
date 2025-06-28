<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('history_perubahan_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbingan_ta_id')->constrained('bimbingan_ta')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->date('tanggal_lama');
            $table->time('jam_lama')->nullable();
            $table->date('tanggal_baru');
            $table->time('jam_baru')->nullable();
            $table->text('alasan_perubahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_perubahan_jadwals');
    }
};
