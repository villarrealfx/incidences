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
        Schema::table('distribution_transformers', function (Blueprint $table) {
            $table->foreignId('transformer_bank_id')->nullable()->default(null)->references('id')->on('transformer_banks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribution_transformers', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'transformer_bank_id',
            ]));
        });
    }
};
