<?php

namespace App\Filament\Resources\ReporteCajaResource\Widgets;

use App\Models\Prestamo;
use App\Models\Capital;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Capital Actual', $this->formatearMoneda($this->getCapitalActual()))
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Capital Prestado', $this->formatearMoneda($this->getCapitalPrestado()))
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
            Stat::make('Cierre de Caja', $this->getFechaCierreCaja())
                ->chart([3, 5, 7, 1, 9, 8, 10])
                ->color('warning'),
        ];
    }

    // Obtener el monto actual de la tabla reporte_caja menos el capital prestado del mes actual
    public function getCapitalActual(): float
    {
        $monto = Capital::value('monto') ?? 0;
        return $monto;
    }

    // Sumar el monto_total de préstamos realizados en el mes actual
    public function getCapitalPrestado(): float
    {

        return Prestamo::sum('monto');
    }

    // Obtener la fecha del cierre de caja (último día del mes actual)
    public function getFechaCierreCaja(): string
    {
        return Carbon::now()->endOfYear()->format('d M, Y');
    }

    // Formatear un número como moneda
    public function formatearMoneda($monto): string
    {
        return '$' . number_format($monto, 0, ',', '.');
    }
}
