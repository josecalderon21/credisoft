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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('apertura')->nullable();
            $table->dateTime('cierre')->nullable();
            $table->decimal('capital_inicial', 15, 2);
            $table->decimal('capital_final', 15, 2)->nullable();
            $table->decimal('capital_prestado', 15, 2)->nullable();
            $table->decimal('refuerzo', 15, 2)->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
