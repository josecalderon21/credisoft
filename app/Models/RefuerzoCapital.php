<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefuerzoCapital extends Model
{
    use HasFactory;

    protected $table = 'refuerzo_capitals'; // Nombre de la tabla

    protected $fillable = [
        'monto_refuerzo',
        'reporte_caja_id'
    ];



    
    

    public function reporteCaja()
    {
        return $this->belongsTo(ReporteCaja::class);
    }

    public function capital()
    {
        return $this->belongsTo(Capital::class);
    }
}
