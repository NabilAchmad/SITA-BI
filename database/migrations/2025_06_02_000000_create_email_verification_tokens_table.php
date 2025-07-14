<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Membuat tabel untuk menyimpan token verifikasi email (OTP)
        Schema::create('email_verification_tokens', function (Blueprint $table) {
            $table->id();
            
            // Menggunakan 'email' sebagai pengenal, bukan 'user_id'
            // Index ditambahkan untuk mempercepat pencarian token berdasarkan email.
            $table->string('email')->index(); 
            
            // Token OTP yang unik untuk setiap entri
            $table->string('token')->unique();
            
            // Timestamp kapan token dibuat, untuk memeriksa masa berlaku
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verification_tokens');
    }
};
