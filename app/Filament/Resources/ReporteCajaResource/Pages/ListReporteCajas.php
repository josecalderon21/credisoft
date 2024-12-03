<?php

namespace App\Filament\Resources\ReporteCajaResource\Pages;

use App\Filament\Resources\ReporteCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReporteCajas extends ListRecords
{
    protected static string $resource = ReporteCajaResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            ReporteCajaResource\Widgets\StatsOverview::class,
        ];
    }
    protected function canCreate(): bool
    {
        // Oculta el botón si ya existe al menos un registro
        return \App\Models\ReporteCaja::count() === 0;
    }
    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
