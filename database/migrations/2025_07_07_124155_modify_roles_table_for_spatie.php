<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // 1. Ubah nama kolom 'nama_role' menjadi 'name'
            $table->renameColumn('nama_role', 'name');

            // 2. Tambahkan kolom 'guard_name' yang dibutuhkan Spatie
            $table->string('guard_name')->default('web')->after('name');

            // 3. (Opsional) Menyesuaikan kolom deskripsi jika perlu
            $table->string('deskripsi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->renameColumn('name', 'nama_role');
            $table->dropColumn('guard_name');
        });
    }
};