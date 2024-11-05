<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->decimal('valor_cuota', 10, 2)->nullable()->after('monto_total');
        });
    }
    
    public function down()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropColumn('valor_cuota');
        });
    }
    
};
