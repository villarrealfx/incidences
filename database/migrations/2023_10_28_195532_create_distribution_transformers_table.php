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
        Schema::create('distribution_transformers', function (Blueprint $table) {
            $table->id();

            $table->string('brand')->nullable()->default(null);
            $table->string('serial')->nullable()->default(null);
            $table->integer('manufacturing_year')->default(0);

            $table->enum('phases', [1, 2, 3])->default(1);
            $table->enum('mounting', ['POSTE', 'PEDESTAL', 'CÁMARA SUBTERRÁNEA'])->default('POSTE');
            $table->enum('isolation', ['LÍQUIDO', 'SECO'])->default('LÍQUIDO');
            $table->enum('winding', ['ALUMINIO', 'COBRE'])->default('ALUMINIO');

            $table->float('high_voltage')->default(13.8);
            $table->string('low_voltage')->default('120-240');
            $table->float('capacity')->default(0.0);
            $table->string('bil')->default('125/30');
            $table->float('weight')->default(0.0);
            $table->integer('tap')->default(3);

            $table->boolean('operative')->default(true);

            $table->date('installation_date')->nullable()->default(null);
            $table->date('uninstall_date')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_transformers');
    }
};
