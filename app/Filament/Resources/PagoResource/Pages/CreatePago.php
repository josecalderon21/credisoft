<?php

namespace App\Filament\Resources\PagoResource\Pages;

use App\Filament\Resources\PagoResource;
use App\Models\Prestamo;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;


    /* protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verificar si 'prestamo_id' y 'monto_abonado' existen en el array $data
        if (isset($data['prestamo_id'], $data['monto_abonado'])) {
            // Obtener el préstamo relacionado para calcular el saldo pendiente
            $prestamo = Prestamo::find($data['prestamo_id']);

            if ($prestamo) {
                $deudaTotal = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                $saldoPendiente = $deudaTotal - $data['monto_abonado'];

                // Verificar que el monto abonado no sea mayor a la deuda total
                if ($data['monto_abonado'] > $deudaTotal) {
                    Notification::make()
                        ->title('Error')
                        ->body('El monto abonado no puede ser mayor que la deuda total.')
                        ->danger()
                        ->send();
                    $data['monto_abonado'] = null;
                    $data['saldo_pendiente'] = $deudaTotal;
                } else {
                    // Asignar el saldo pendiente calculado al campo antes de guardar
                    $data['saldo_pendiente'] = max($saldoPendiente, 0); // Evitar negativos
                }
            }
        } else {
            // En caso de que 'monto_abonado' no esté definido, asignar un valor por defecto
            $data['saldo_pendiente'] = $prestamo->monto_total ?? 0;
        }

        return $data;
    } */
}
