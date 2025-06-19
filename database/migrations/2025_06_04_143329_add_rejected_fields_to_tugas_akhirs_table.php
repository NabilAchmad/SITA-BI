<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


    /**
     * Run the migrations.
     */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->unsignedBigInteger('rejected_by')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    public function down(): void
    {
        Schema::table('tugas_akhir', function (Blueprint $table) {
            $table->dropColumn(['rejected_by', 'rejected_at']);
        });
    }
};
