<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftaran_sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhir')->onDelete('cascade');

            // Status untuk melacak proses verifikasi berkas oleh admin
            $table->enum('status_verifikasi', [
                'menunggu_verifikasi',
                'berkas_tidak_lengkap',
                'disetujui'
            ])->default('menunggu_verifikasi');

            // Kolom untuk menyimpan path file berkas yang diunggah mahasiswa
            $table->string('file_skripsi_final');
            $table->string('file_cek_plagiarisme');
            $table->string('file_transkrip');

            // Catatan dari admin jika berkas perlu diperbaiki
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftaran_sidang');
    }
};
