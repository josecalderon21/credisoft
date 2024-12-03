<?php

namespace App\Filament\Resources\ReporteCajaResource\Pages;

use App\Filament\Resources\ReporteCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReporteCaja extends EditRecord
{
    protected static string $resource = ReporteCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
