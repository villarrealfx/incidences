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
        Schema::create('transformer_banks', function (Blueprint $table) {
            $table->id();
            $table->string('connection_group')->nullable()->default(null);
            $table->boolean('private')->default(false);
            $table->foreignId('fuse_cutout_id')->references('id')->on('fuse_cutouts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transformer_banks');
    }
};
