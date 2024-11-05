<?php

namespace App\Filament\Resources\CajaResource\Pages;

use App\Filament\Resources\CajaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCaja extends CreateRecord
{
    protected static string $resource = CajaResource::class;

  /*   protected function afterCreate(): void
    {
        // Obtener el capital inicial de la nueva caja
        $capitalInicial = $this->record->capital_inicial;

        // Actualizar el capital actual
        CajaResource::actualizarCapitalActual($capitalInicial, 0);
    } */
}
