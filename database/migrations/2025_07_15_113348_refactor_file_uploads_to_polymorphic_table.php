<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        // Langkah 1: Hapus kolom nama_file_asli dari tabel dokumen_ta
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->dropColumn('nama_file_asli');
        });

        // Langkah 2: Hapus semua kolom terkait file dari pendaftaran_sidang
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            $table->dropColumn([
                'file_naskah_ta',
                'file_rapor',
                'file_toeic',
                'file_ijazah_slta',
                'file_bebas_jurusan',
            ]);
            // Jika Anda sudah menambahkan nama asli sebelumnya, hapus juga
            // $table->dropColumn(['nama_asli_naskah_ta', 'nama_asli_rapor', ...]);
        });

        // Langkah 3: Buat tabel baru untuk file uploads dengan relasi polimorfik
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');      // Path penyimpanan, mis: pendaftaran_sidang/1/abc.pdf
            $table->string('original_name');  // Nama asli file, mis: Naskah TA - Budi.pdf
            $table->string('file_type')->nullable(); // Tipe file, mis: 'naskah_ta', 'toeic'
            $table->morphs('fileable');       // Membuat 'fileable_id' dan 'fileable_type'
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        // Langkah 1 (dibalik): Hapus tabel file_uploads
        Schema::dropIfExists('file_uploads');

        // Langkah 2 (dibalik): Kembalikan kolom ke tabel pendaftaran_sidang
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            $table->string('file_naskah_ta')->after('status_pembimbing_2');
            $table->string('file_rapor')->after('file_naskah_ta');
            $table->string('file_toeic')->after('file_rapor');
            $table->string('file_ijazah_slta')->after('file_toeic');
            $table->string('file_bebas_jurusan')->after('file_ijazah_slta');
        });

        // Langkah 3 (dibalik): Kembalikan kolom ke tabel dokumen_ta
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->string('nama_file_asli')->nullable()->after('file_path');
        });
    }
};
