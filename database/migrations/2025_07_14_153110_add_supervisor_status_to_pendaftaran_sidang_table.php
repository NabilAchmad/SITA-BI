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
        // Menggunakan Schema::table untuk memodifikasi tabel yang sudah ada
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            
            // Menambahkan kolom status untuk Dosen Pembimbing 1
            // Ditempatkan setelah kolom 'status_verifikasi' agar struktur lebih rapi
            $table->enum('status_pembimbing_1', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->after('status_verifikasi')
                  ->comment('Status persetujuan dari Dosen Pembimbing 1');

            // Menambahkan kolom status untuk Dosen Pembimbing 2
            // Ditempatkan setelah kolom 'status_pembimbing_1'
            $table->enum('status_pembimbing_2', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->after('status_pembimbing_1')
                  ->comment('Status persetujuan dari Dosen Pembimbing 2');

            // Menambahkan kolom catatan untuk Dosen Pembimbing 1
            // Ditempatkan setelah kolom 'catatan_admin'
            $table->text('catatan_pembimbing_1')->nullable()->after('catatan_admin');

            // Menambahkan kolom catatan untuk Dosen Pembimbing 2
            // Ditempatkan setelah kolom 'catatan_pembimbing_1'
            $table->text('catatan_pembimbing_2')->nullable()->after('catatan_pembimbing_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Method 'down' ini akan menghapus kolom yang ditambahkan jika migrasi di-rollback
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            $table->dropColumn([
                'status_pembimbing_1',
                'status_pembimbing_2',
                'catatan_pembimbing_1',
                'catatan_pembimbing_2'
            ]);
        });
    }
};
