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
        Schema::table('circuit_loads', function (Blueprint $table) {
            $table->dateTime('datetime')->default('2000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('circuit_loads', function (Blueprint $table) {
            $table->dropColumn('datetime');
        });
    }
};
