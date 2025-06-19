<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->enum('peran', ['pembimbing1', 'pembimbing2'])->after('dosen_id');
            $table->integer('sesi_ke')->default(1)->after('peran');
        });
    }

    public function down(): void
    {
        Schema::table('bimbingan_ta', function (Blueprint $table) {
            $table->dropColumn(['peran', 'sesi_ke']);
        });
    }
};
