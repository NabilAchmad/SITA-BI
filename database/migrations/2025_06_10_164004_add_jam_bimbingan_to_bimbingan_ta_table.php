<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamBimbinganToBimbinganTaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->time('jam_bimbingan')->nullable()->after('tanggal_bimbingan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->dropColumn('jam_bimbingan');
        });
    }
}
