<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Metode ini akan merombak kolom validasi pada tabel dokumen_ta.
     */
    public function up(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            if (!Schema::hasColumn('dokumen_ta', 'divalidasi_oleh_p1')) {
                $table->foreignId('divalidasi_oleh_p1')
                    ->nullable()
                    ->after('status_validasi')
                    ->constrained('dosens') // default ke kolom id
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('dokumen_ta', 'divalidasi_oleh_p2')) {
                $table->foreignId('divalidasi_oleh_p2')
                    ->nullable()
                    ->after('divalidasi_oleh_p1')
                    ->constrained('dosens')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Metode ini akan mengembalikan struktur tabel ke kondisi semula jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->dropForeign(['divalidasi_oleh_p1']);
            $table->dropForeign(['divalidasi_oleh_p2']);

            $table->dropColumn(['divalidasi_oleh_p1', 'divalidasi_oleh_p2']);

            // Tidak perlu mengembalikan scanned_by/divalidasi_oleh karena kolom itu sudah tidak ada
            // Tapi jika kamu memang ingin mengembalikan, tambahkan kode ini:

            $table->foreignId('divalidasi_oleh')
                ->nullable()
                ->after('status_validasi')
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('scanned_by')
                ->nullable()
                ->after('scan_at')
                ->constrained('users')
                ->onDelete('set null');
        });
    }
};
