<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'prestamo_id',
        'cuota_id',
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

    public function capital()
    {
        return $this->belongsTo(Capital::class);
    }

    // Pago.php

    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'cuota_id'); // Ajusta el nombre de la columna si es diferente
    }

    // Pago.php

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }
    public function estadoDeDeuda()
    {
        return $this->belongsTo(EstadoDeDeuda::class);
    }
    protected static function booted()
    {
        static::created(function ($pago) {
            $prestamo = Prestamo::find($pago->prestamo_id);

            // Verificar que se ha seleccionado una cuota y que el tipo de pago no sea total
            if ($pago->tipo_pago === 'cuota' && $pago->cuota) {
                if ($pago->monto_abonado >= $pago->cuota->total) {
                    // Si el monto abonado es suficiente para cubrir la cuota completa
                    $pago->cuota->update(['estado' => 'pagada']);
                }
            } elseif ($pago->tipo_pago === 'total') {
                // Cambiar el estado de todas las cuotas pendientes a "pagada" si es un pago total
                if ($prestamo) {
                    Cuota::where('prestamo_id', $prestamo->id)
                        ->where('estado', 'pendiente')
                        ->update(['estado' => 'pagada']);
                }
            } elseif ($pago->tipo_pago === 'otro') {
                // Si se selecciona pagar "Otro Valor"
                if ($pago->cuota) {
                    $saldoCuota = $pago->cuota->total - $pago->cuota->pagos()->sum('monto_abonado');
                    if ($pago->monto_abonado >= $saldoCuota) {
                        // Marcar la cuota como pagada si el monto abonado cubre el saldo pendiente de la cuota
                        $pago->cuota->update(['estado' => 'pagada']);
                        // **Actualizar el capital disponible** (El pago abona al capital)
              
                    }
                }

                // Si el pago cubre la deuda total pendiente, marcar todas las cuotas como pagadas
                $saldoPrestamo = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                if ($pago->monto_abonado >= $saldoPrestamo) {
                    Cuota::where('prestamo_id', $prestamo->id)
                        ->where('estado', 'pendiente')
                        ->update(['estado' => 'pagada']);
                }
            }
            $capital = \App\Models\Capital::first(); // Asumiendo que solo hay un registro de capital
            if ($capital) {
                $capital->increment('monto', $pago->monto_abonado);
            }

            if ($prestamo) {
                // Verificar si el saldo pendiente del préstamo es 0
                $saldoPendiente = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
    
                if ($saldoPendiente <= 0) {
                    // Cambiar el estado del préstamo a "cancelado"
                    $prestamo->update(['estado' => 'cancelado']);
                }
            }
        });
    }

    public function gananciaPorPago()
    {
        $tasaInteres = $this->prestamo->tasa_interes;
        if($this->tipo_pago === 'total'){
            $ganancia = $this->monto_abonado*($tasaInteres/100);
        }
        else{
            $monto = $this->prestamo->monto; // Monto del préstamo
            $numeroCuotas = $this->prestamo->numero_cuotas; // Número de cuotas del préstamo
             // Tasa de interés del préstamo
    
            // Cálculo de la ganancia
            $ganancia = ($monto / $numeroCuotas) * ($tasaInteres / 100); // Ganancia por cuota
        }

        return $ganancia;
    }

    public function getTasaInteres()
    {
        $tasa = $this->prestamo->tasa_interes;
        return $tasa;
    }

    public function getCapital()
    {
        $tasaInteres = $this->prestamo->tasa_interes;
        if($this->tipo_pago === 'total'){
            $final = $this->monto_abonado-($this->monto_abonado*($tasaInteres/100));
        }
        else{
            $capital = $this->prestamo->monto;
            $cuotas = $this->prestamo->numero_cuotas;
            $final = $capital / $cuotas;
        }
  
        return $final;
    }

    public function getHora()
    {
        $hora = $this->created_at;
        return $hora;
    }
}
