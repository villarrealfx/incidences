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
        Schema::create('disconnectors', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255)->default('SC-');
            $table->text('address')->nullable();

            $table->boolean('status')->default(false);
            $table->boolean('operative')->default(true);
            $table->boolean('backbone')->default(false);
            $table->boolean('link')->default(false);

            $table->float('load_percentage', 8,2)->default(0.0);
            $table->float('distance', 8,2)->default(0.0);

            $table->foreignId('circuit_one_id')->references('id')->on('circuits');
            $table->foreignId('circuit_two_id')->references('id')->on('circuits');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disconnectors');
    }
};
