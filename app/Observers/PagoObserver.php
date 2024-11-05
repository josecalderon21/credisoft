<?php

namespace App\Observers;

use App\Models\Pago;

class PagoObserver
{
    /**
     * Handle the Pago "created" event.
     */
    public function saved(Pago $pago)
    {
        // Obtener el préstamo asociado al pago
        $prestamo = $pago->prestamo;

        // Verificar si el saldo pendiente es 0
        if ($pago->saldo_pendiente <= 0 && $prestamo->estado !== 'completado') {
            // Cambiar el estado del préstamo a "completado"
            $prestamo->estado = 'completado';
            $prestamo->save();
        }
    }
    public function created(Pago $pago)
{
    /* $prestamo = $pago->prestamo;

    // Actualiza el saldo pendiente
    $saldoPendiente = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
    $prestamo->saldo_pendiente = $saldoPendiente;
    $prestamo->estado = $saldoPendiente <= 0 ? 'completado' : 'activo';
    $prestamo->save();
 */
}

    /**
     * Handle the Pago "updated" event.
     */
    public function updated(Pago $pago): void
    {
        //
    }

    /**
     * Handle the Pago "deleted" event.
     */
    public function deleted(Pago $pago): void
    {
        //
    }

    /**
     * Handle the Pago "restored" event.
     */
    public function restored(Pago $pago): void
    {
        //
    }

    /**
     * Handle the Pago "force deleted" event.
     */
    public function forceDeleted(Pago $pago): void
    {
        //
    }
}
