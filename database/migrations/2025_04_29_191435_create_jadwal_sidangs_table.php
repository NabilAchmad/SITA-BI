<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_id')->constrained('sidang')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->foreignId('ruangan_id')->constrained('ruangan')->onDelete('cascade');
            $table->timestamps();
<<<<<<< HEAD
=======
<<<<<<< HEAD
            $table->softDeletes();
=======
>>>>>>> 905ddd514a1f02e7724f6a26e444a4bf3ee356ee
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_sidang');
    }
};
