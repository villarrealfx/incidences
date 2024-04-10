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
        Schema::create('substations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('level', ['Transmisión', 'Distribución'])->default('Transmisión');
            $table->string('voltage_level')->default('115 kV');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('substations');
    }
};
