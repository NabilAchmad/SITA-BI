<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peran_dosen_ta', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
=======
<<<<<<< HEAD
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
=======
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
>>>>>>> 905ddd514a1f02e7724f6a26e444a4bf3ee356ee
>>>>>>> 9b746f97d8fd6b9b94568020d81c60f0e486f87a
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->onDelete('cascade');
            $table->enum('peran', [
                'pembimbing1',
                'pembimbing2',
                'penguji1',
                'penguji2',
                'penguji3',
                'penguji4'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peran_dosen_ta');
    }
};
