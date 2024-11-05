<?php

namespace App\Filament\Resources\CuotaResource\Pages;

use App\Filament\Resources\CuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuota extends EditRecord
{
    protected static string $resource = CuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
