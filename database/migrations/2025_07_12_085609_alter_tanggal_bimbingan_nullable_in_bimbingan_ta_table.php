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
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->date('tanggal_bimbingan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->date('tanggal_bimbingan')->nullable(false)->change();
        });
    }
};
