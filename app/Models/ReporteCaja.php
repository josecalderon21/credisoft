<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteCaja extends Model
{
    use HasFactory;

    protected $table = 'reporte_cajas'; // Nombre de la tabla

    protected $fillable = [
        'fecha', 'monto','capital_prestado'
    ];

    
    public function refuerzos()
    {
        return $this->hasMany(RefuerzoCapital::class);
    }
}

