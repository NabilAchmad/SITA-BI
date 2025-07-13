<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa import DB

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Perintah 1: Tambahkan kolom foreign key HANYA JIKA BELUM ADA
        // Ini akan mencegah error 'Duplicate column name' jika migrasi dijalankan ulang.
        if (!Schema::hasColumn('sidang', 'pendaftaran_sidang_id')) {
            Schema::table('sidang', function (Blueprint $table) {
                $table->foreignId('pendaftaran_sidang_id')
                    ->after('tugas_akhir_id')
                    ->nullable()
                    ->constrained('pendaftaran_sidang')
                    ->onDelete('set null');
            });
        }

        // Perintah 2: Lakukan semua perubahan kolom dengan Raw SQL untuk menghindari error sintaks
        // Perintah CHANGE COLUMN akan menimpa kolom yang ada, jadi aman untuk dijalankan.
        DB::statement("
            ALTER TABLE sidang
            CHANGE COLUMN jenis_sidang jenis_sidang ENUM('akhir') NOT NULL DEFAULT 'akhir',
            CHANGE COLUMN status status_hasil ENUM('dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus') NOT NULL DEFAULT 'dijadwalkan'
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Perintah 1 (dibalik): Lakukan rollback perubahan kolom dengan Raw SQL
        DB::statement("
            ALTER TABLE sidang
            CHANGE COLUMN jenis_sidang jenis_sidang ENUM('proposal', 'akhir') NOT NULL,
            CHANGE COLUMN status_hasil status ENUM('menunggu', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus') NOT NULL DEFAULT 'dijadwalkan'
        ");

        // Perintah 2 (dibalik): Hapus kolom foreign key JIKA ADA
        Schema::table('sidang', function (Blueprint $table) {
            if (Schema::hasColumn('sidang', 'pendaftaran_sidang_id')) {
                $table->dropForeign(['pendaftaran_sidang_id']);
                $table->dropColumn('pendaftaran_sidang_id');
            }
        });
    }
};
