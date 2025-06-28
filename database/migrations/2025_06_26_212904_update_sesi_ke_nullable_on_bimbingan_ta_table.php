<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSesiKeNullableOnBimbinganTaTable extends Migration
{
    public function up()
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->integer('sesi_ke')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->integer('sesi_ke')->default(1)->change(); // rollback ke semula
        });
    }
}
