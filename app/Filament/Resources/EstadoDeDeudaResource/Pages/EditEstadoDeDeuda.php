<?php

namespace App\Filament\Resources\EstadoDeDeudaResource\Pages;

use App\Filament\Resources\EstadoDeDeudaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstadoDeDeuda extends EditRecord
{
    protected static string $resource = EstadoDeDeudaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
