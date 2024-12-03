<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'numero_cuota',
        'fecha_vencimiento',
        'capital',
        'interes',
        'total',
        'estado',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    // Relación con estado de deuda
    public function estadoDeDeuda()
    {
        return $this->hasMany(EstadoDeDeuda::class, 'prestamo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
