<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // <-- Penting untuk menjalankan statement SQL mentah
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
        // Menambahkan FULLTEXT index ke kolom 'judul' pada tabel 'judul_tugas_akhirs'
        // Ini akan secara drastis mempercepat query MATCH() AGAINST()
        DB::statement('ALTER TABLE judul_tugas_akhirs ADD FULLTEXT(judul)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Logika untuk membatalkan migrasi (rollback)
        // Kita akan menghapus index yang sudah dibuat
        Schema::table('judul_tugas_akhirs', function (Blueprint $table) {
            // Nama default untuk FULLTEXT index pada satu kolom adalah nama kolom itu sendiri
            $table->dropIndex('judul');
        });
    }
};
