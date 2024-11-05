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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade'); // Relación con Clientes
            $table->decimal('tasa_interes', 5, 2); // Tasa de Interés
            $table->integer('numero_cuotas'); // Número de Cuotas
            $table->enum('tipo_cuota', ['anual', 'semestral', 'mensual', 'quincenal', 'diario']); // Tipo de Cuota
            $table->decimal('monto', 12, 2); // Monto Prestado
            $table->decimal('intereses_generados', 12, 2)->nullable(); // Intereses Generados
            $table->decimal('monto_total', 12, 2)->nullable(); // Monto Total a Pagar
            $table->string('pdf')->nullable(); // Ruta del archivo PDF generado con los detalles del préstamo
            $table->timestamps(); // Timestamps de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
