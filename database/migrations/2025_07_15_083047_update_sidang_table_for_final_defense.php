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
        Schema::table('sidang', function (Blueprint $table) {
            // Menghapus kolom yang tidak lagi diperlukan
            $table->dropColumn('jenis_sidang');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sidang', function (Blueprint $table) {
            // Mengembalikan kolom jika migrasi di-rollback
            $table->enum('jenis_sidang', ['proposal', 'akhir'])->default('akhir')->after('pendaftaran_sidang_id');
            $table->enum('status', ['menunggu', 'menunggu_verifikasi', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus'])->default('menunggu_verifikasi')->after('jenis_sidang');
        });
    }
};
