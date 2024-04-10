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
            $table->foreignId('cause_id')->nullable()->default(null)->references('id')->on('causes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->dropColumn('cause_id');
        });
    }
};
