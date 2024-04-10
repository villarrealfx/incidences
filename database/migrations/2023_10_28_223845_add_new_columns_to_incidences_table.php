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
            $table->dateTime('finish')->nullable()->default(null);
            $table->boolean('active')->default(true);
            $table->enum('operation', ['DISPARO', 'APERTURA'])->default('DISPARO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'finish',
                'active',
                'operation',
            ]));
        });
    }
};
