<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->enum('jenis_sidang', ['sempro', 'akhir'])->after('waktu_selesai');
        });
    }

    public function down()
    {
        Schema::table('jadwal_sidang', function (Blueprint $table) {
            $table->dropColumn('jenis_sidang');
        });
    }
};
