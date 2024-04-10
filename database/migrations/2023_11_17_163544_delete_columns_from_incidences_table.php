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
            $table->dropColumn([
                'system',
                'cause',
                'subcause',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->string('system')->default('DISTRIBUCION');
            $table->string('cause');
            $table->string('subcause');
        });
    }
};
