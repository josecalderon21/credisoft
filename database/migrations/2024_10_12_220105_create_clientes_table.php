<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->enum('tipo_documento', ['cedula_de_ciudadania', 'nit', 'cedula_extranjera']);
            $table->string('numero_documento')->unique();
            $table->string('telefono');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('email')->unique();

            // Campos para el codeudor
            $table->string('codeudor_nombres');
            $table->string('codeudor_apellidos');
            $table->enum('codeudor_tipo_documento', ['cedula_de_ciudadania', 'nit', 'cedula_extranjera']);
            $table->string('codeudor_numero_documento');
            $table->string('codeudor_telefono');
            $table->string('codeudor_ciudad');
            $table->string('codeudor_direccion');
            $table->string('codeudor_email');
            $table->string('archivo')->nullable();  // Campo para almacenar el nombre del archivo

            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clientes');
    }
};
