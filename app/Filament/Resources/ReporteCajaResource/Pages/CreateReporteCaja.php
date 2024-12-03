<?php

namespace App\Filament\Resources\ReporteCajaResource\Pages;

use App\Filament\Resources\ReporteCajaResource;
use App\Models\CapitalCaja;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReporteCaja extends CreateRecord
{
    protected static string $resource = ReporteCajaResource::class;
    protected $fillable = [
        'fecha',
        'monto',
    ];

    
   
}
