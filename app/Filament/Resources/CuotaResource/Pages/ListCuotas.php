<?php

namespace App\Filament\Resources\CuotaResource\Pages;

use App\Filament\Resources\CuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCuotas extends ListRecords
{
    protected static string $resource = CuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
