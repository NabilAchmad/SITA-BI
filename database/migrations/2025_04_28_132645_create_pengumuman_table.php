<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->enum('audiens', ['registered_users', 'guest', 'all_users'])->default('all_users');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
