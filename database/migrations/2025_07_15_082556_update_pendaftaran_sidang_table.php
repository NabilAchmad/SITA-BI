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
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            // 1. Mengubah nama kolom yang sudah ada agar lebih sesuai
            $table->renameColumn('file_skripsi_final', 'file_naskah_ta');
            $table->renameColumn('file_transkrip', 'file_rapor');

            // 2. Menambahkan kolom baru untuk berkas persyaratan
            // Penempatan kolom baru setelah kolom file_rapor untuk kerapian
            $table->string('file_toeic')->after('file_rapor');
            $table->string('file_ijazah_slta')->after('file_toeic');
            $table->string('file_bebas_jurusan')->after('file_ijazah_slta');

            // 3. Menghapus kolom file_cek_plagiarisme
            $table->dropColumn('file_cek_plagiarisme');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            // 1. Mengembalikan nama kolom ke nama semula
            $table->renameColumn('file_naskah_ta', 'file_skripsi_final');
            $table->renameColumn('file_rapor', 'file_transkrip');

            // 2. Menghapus kolom yang ditambahkan
            $table->dropColumn(['file_toeic', 'file_ijazah_slta', 'file_bebas_jurusan']);

            // 3. Menambahkan kembali kolom file_cek_plagiarisme
            $table->string('file_cek_plagiarisme')->after('file_transkrip');
        });
    }
};
