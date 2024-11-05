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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained(); // Relación con el cliente
            $table->foreignId('prestamo_id')->constrained(); // Relación con el préstamo
            $table->decimal('monto_abonado', 10, 2);
            $table->enum('tipo_pago', ['cuota', 'total', 'otro']);
            $table->enum('modalidad_pago', ['efectivo', 'transferencia']);
            $table->string('numero_comprobante')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
