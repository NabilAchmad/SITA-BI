<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatanBimbinganTable extends Migration
{
    public function up()
    {
        Schema::create('catatan_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bimbingan_ta_id');
            $table->enum('author_type', ['mahasiswa', 'dosen']); // siapa yang buat catatan
            $table->unsignedBigInteger('author_id'); // id mahasiswa atau dosen
            $table->text('catatan');
            $table->timestamps();

            $table->foreign('bimbingan_ta_id')->references('id')->on('bimbingan_ta')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatan_bimbingan');
    }
}
