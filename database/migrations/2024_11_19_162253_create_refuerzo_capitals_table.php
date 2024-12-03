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
        Schema::create('refuerzo_capitals', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('reporte_caja_id'); // Relación con `capital_cajas`
        $table->decimal('monto_refuerzo', 15, 2); // Monto del refuerzo
        $table->timestamps();
        

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refuerzo_capitals');
    }
};
