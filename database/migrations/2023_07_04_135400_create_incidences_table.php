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
        Schema::create('incidences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('system')->default('DISTRIBUCION');
            $table->time('start');
            $table->time('duration');
            $table->float('load')->default(0.0);
            $table->float('frequency', 8, 4)->default(0.0);
            $table->float('average', 8, 4)->default(0.0);
            $table->float('tti', 8, 4)->default(0.0);
            $table->string('signal');
            $table->string('cause');
            $table->string('subcause');
            $table->text('observations');
            $table->foreignId('circuit_id')->references('id')->on('circuits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidences');
    }
};
