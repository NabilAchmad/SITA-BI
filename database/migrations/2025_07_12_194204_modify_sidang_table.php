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
            // 1. Tambahkan foreign key ke tabel pendaftaran_sidang
            // Kolom ini akan menghubungkan sidang yang dijadwalkan dengan data pendaftarannya.
            $table->foreignId('pendaftaran_sidang_id')
                ->after('tugas_akhir_id') // Posisikan setelah tugas_akhir_id
                ->nullable() // Buat nullable sementara untuk data lama
                ->constrained('pendaftaran_sidang')
                ->onDelete('cascade');

            // 2. Hapus 'proposal' dari enum 'jenis_sidang'
            // Mengubah kolom menjadi hanya 'akhir' sesuai permintaan.
            $table->enum('jenis_sidang', ['akhir'])->default('akhir')->change();

            // 3. Ganti nama kolom 'status' menjadi 'status_hasil' agar lebih jelas
            $table->renameColumn('status', 'status_hasil');
        });

        // Lakukan perubahan enum pada kolom yang sudah diganti namanya
        Schema::table('sidang', function (Blueprint $table) {
            // 4. Sesuaikan nilai enum pada kolom 'status_hasil'
            // Menghapus status 'menunggu' karena sudah ditangani di tabel pendaftaran.
            $table->enum('status_hasil', [
                'dijadwalkan',
                'lulus',
                'lulus_revisi',
                'tidak_lulus'
            ])->default('dijadwalkan')->change();
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
            // Mengembalikan perubahan jika migrasi di-rollback
            $table->dropForeign(['pendaftaran_sidang_id']);
            $table->dropColumn('pendaftaran_sidang_id');

            $table->renameColumn('status_hasil', 'status');
        });

        Schema::table('sidang', function (Blueprint $table) {
            // Kembalikan enum seperti semula
            $table->enum('jenis_sidang', ['proposal', 'akhir'])->change();
            $table->enum('status', [
                'menunggu',
                'dijadwalkan',
                'lulus',
                'lulus_revisi',
                'tidak_lulus'
            ])->default('dijadwalkan')->change();
        });
    }
};
