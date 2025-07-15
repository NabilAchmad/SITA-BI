<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sidang', function (Blueprint $table) {
            $table->enum('status_hasil', [
                'menunggu_penjadwalan',
                'dijadwalkan',
                'lulus',
                'lulus_revisi',
                'tidak_lulus'
            ])->default('menunggu_penjadwalan')->change();
        });
    }

    public function down()
    {
        Schema::table('sidang', function (Blueprint $table) {
            $table->enum('status_hasil', [
                'dijadwalkan',
                'lulus',
                'lulus_revisi',
                'tidak_lulus'
            ])->default('dijadwalkan')->change();
        });
    }
};
