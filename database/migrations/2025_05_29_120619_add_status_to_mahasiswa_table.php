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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'cuti', 'dropout', 'alumni'])->default('aktif')->after('kelas');
        });
    }

    public function down()
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
