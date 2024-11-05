<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'prestamo_id',
        'cliente_id',
        'monto_abonado',
        'tipo_pago',
        'modalidad_pago',
        'numero_comprobante',
        'saldo_pendiente',
    ];



// Pago.php
public function cliente()
{
    return $this->belongsTo(Cliente::class);
}

// Pago.php

public function cuota()
{
    return $this->belongsTo(Cuota::class, 'cuota_id'); // Ajusta el nombre de la columna si es diferente
}

// Pago.php

public function prestamo()
{
    return $this->belongsTo(Prestamo::class);
}


}



    
/* 
    protected static function booted()
{
    static::creating(function ($pago) {
        $prestamo = Prestamo::find($pago->prestamo_id);

        if ($prestamo) {
            $montoTotal = $prestamo->monto_total;
            $pagosRealizados = $prestamo->pagos()->sum('monto_abonado');

            // Calcula el saldo pendiente
            $pago->saldo_pendiente = $montoTotal - ($pagosRealizados + $pago->monto_abonado);
        }
    });



        // Evento al actualizar un pago existente
        static::updating(function ($pago) {
            $prestamo = Prestamo::find($pago->prestamo_id);

            if ($prestamo) {
                // Recalcular todos los pagos, incluyendo el actual
                $pagosRealizados = $prestamo->pagos()
                    ->where('id', '!=', $pago->id) // Excluir el pago que estamos actualizando
                    ->sum('monto_abonado');
                
                $pago->saldo_pendiente = $prestamo->monto_total - ($pagosRealizados + $pago->monto_abonado);
            }
        });
    } */


