<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDeDeuda extends Model
{
    use HasFactory;

    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    } 
     // Relación con cuotas (si aplica)
     public function cuotas()
     {
         return $this->hasMany(Cuota::class);
     }
     // Relación con pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'estado_de_deuda_id');
    }
    // Relación con el préstamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id'); // 'prestamo_id' es el campo que debería conectar con la tabla prestamos
    }

    

}
