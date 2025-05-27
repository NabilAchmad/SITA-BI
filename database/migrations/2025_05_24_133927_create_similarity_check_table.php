<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('similarity_check', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_ta_id')->constrained('dokumen_ta')->onDelete('cascade');
            $table->float('score')->comment('Skor kemiripan dalam persen');
            $table->json('sources')->nullable()->comment('Detail sumber kemiripan');
            $table->foreignId('checked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('similarity_check');
    }
};
