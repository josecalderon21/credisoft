<?php

namespace App\Filament\Resources\EstadoDeDeudaResource\Pages;

use App\Filament\Resources\EstadoDeDeudaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstadoDeDeudas extends ListRecords
{
    protected static string $resource = EstadoDeDeudaResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
