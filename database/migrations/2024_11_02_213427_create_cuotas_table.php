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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade'); // Relación con la tabla prestamos
            $table->integer('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->decimal('capital', 15, 2); // Capital para la cuota
            $table->decimal('interes', 15, 2); // Interés para la cuota
            $table->decimal('total', 15, 2); // Total a pagar (capital + interés)
            $table->enum('estado', ['pendiente', 'pagada'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
