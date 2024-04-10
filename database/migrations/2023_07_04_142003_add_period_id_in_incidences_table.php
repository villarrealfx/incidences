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
        Schema::table('incidences', function (Blueprint $table) {
            $table->foreignId('period_id')->references('id')->on('periods');
            $table->foreignId('service_center_id')->references('id')->on('service_centers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'period_id',
                'service_centers_id',
            ]));
        });
    }
};
