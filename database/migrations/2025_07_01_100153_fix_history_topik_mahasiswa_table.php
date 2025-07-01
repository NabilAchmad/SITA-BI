<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('history_topik_mahasiswa', function (Blueprint $table) {
            // PERBAIKAN: Hapus kolom lama jika ada
            if (Schema::hasColumn('history_topik_mahasiswa', 'user_id')) {
                // Pertama, hapus foreign key jika ada
                // Laravel akan mencoba mencari nama default, jika gagal, kita coba cara lain
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Abaikan error jika foreign key tidak ditemukan dengan nama default
                }
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('history_topik_mahasiswa', 'status_topik')) {
                $table->dropColumn('status_topik');
            }
            if (Schema::hasColumn('history_topik_mahasiswa', 'tanggal_pemilihan')) {
                $table->dropColumn('tanggal_pemilihan');
            }

            // Tambahkan kolom yang benar sesuai alur bisnis
            if (!Schema::hasColumn('history_topik_mahasiswa', 'mahasiswa_id')) {
                $table->foreignId('mahasiswa_id')->after('id')->constrained('mahasiswa')->onDelete('cascade');
            }
            if (!Schema::hasColumn('history_topik_mahasiswa', 'tawaran_topik_id')) {
                $table->foreignId('tawaran_topik_id')->after('mahasiswa_id')->constrained('tawaran_topik')->onDelete('cascade');
            }
            if (!Schema::hasColumn('history_topik_mahasiswa', 'status')) {
                $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan')->after('tawaran_topik_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_topik_mahasiswa', function (Blueprint $table) {
            // Cek sebelum menghapus untuk menghindari error
            if (Schema::hasColumn('history_topik_mahasiswa', 'mahasiswa_id')) {
                $table->dropForeign(['mahasiswa_id']);
                $table->dropColumn('mahasiswa_id');
            }
            if (Schema::hasColumn('history_topik_mahasiswa', 'tawaran_topik_id')) {
                $table->dropForeign(['tawaran_topik_id']);
                $table->dropColumn('tawaran_topik_id');
            }
            if (Schema::hasColumn('history_topik_mahasiswa', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
