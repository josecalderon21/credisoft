<?php

namespace App\Filament\Resources\CajaResource\Widgets;

use App\Models\Caja;
use App\Models\Prestamo; // Importamos el modelo de Prestamo
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Capital Actual', number_format($this->getCapitalActual(), 2))
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Capital Prestado', number_format($this->getCapitalPrestado(), 2))
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Cierre de Caja', $this->getCapitalCierre())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
        ];
    }

    // Calcula el Capital Actual sumando el capital inicial y refuerzo, y restando el capital prestado
    public static function getCapitalActual()
    {
        $caja = Caja::latest()->first();

        // Obtener la suma del capital inicial y el refuerzo de la caja más reciente
        $capitalInicial = $caja ? $caja->capital_inicial : 0;
        $refuerzo = $caja ? $caja->refuerzo : 0;

        // Restar el total de los montos prestados
        $capitalPrestado = self::getCapitalPrestado();

        return $capitalInicial + $refuerzo - $capitalPrestado;
    }

    // Sumar el monto_total de todos los préstamos en la tabla de préstamos
    public static function getCapitalPrestado()
    {
        return Prestamo::sum('monto_total');
    }

    // Obtener la fecha de cierre de la última caja cerrada
    public static function getCapitalCierre()
    {
        // Obtener la última caja cerrada
        $cajaCerrada = Caja::whereNotNull('cierre')->latest()->first();

        // Verificar si existe una caja cerrada
        if ($cajaCerrada && $cajaCerrada->cierre) {
            // Asegurarse de que 'cierre' sea un objeto Carbon y formatear la fecha
            $fechaCierre = Carbon::parse($cajaCerrada->cierre)->format('d/m/Y H:i');
            return $fechaCierre; // Devolver la fecha de cierre formateada
        }

        return 'Sin cierre'; // Si no hay fecha de cierre, devolver un mensaje alternativo
    }
}
