<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToSidangTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('sidang', 'status')) {
            Schema::table('sidang', function (Blueprint $table) {
                $table->enum('status', ['menunggu', 'dijadwalkan', 'lulus', 'lulus_revisi', 'tidak_lulus'])
                    ->default('dijadwalkan')
                    ->after('jenis_sidang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sidang', 'status')) {
            Schema::table('sidang', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
