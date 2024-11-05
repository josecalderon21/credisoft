<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
namespace App\Filament\Resources\PrestamoResource\Widgets;
use App\Models\Prestamo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Préstamos Registrados', $this->getTotalPrestamos())
                ->chart([5, 3, 9, 6, 7])
                ->color('primary'),

            Stat::make('Préstamos Activos', $this->getPrestamosActivos())
                ->chart([3, 4, 5, 6, 7])
                ->color('success'),

            Stat::make('Préstamos Completados', $this->getPrestamosCompletados())
                ->chart([1, 2, 3, 4, 5])
                ->color('secondary'),
        ];
    }

    // Obtener el número total de préstamos registrados
    protected function getTotalPrestamos()
    {
        return Prestamo::count();
    }

    // Obtener el número total de préstamos activos
    protected function getPrestamosActivos()
    {
        return Prestamo::where('estado', 'activo')->count();
    }

    // Obtener el número total de préstamos completados
    protected function getPrestamosCompletados()
    {
        return Prestamo::where('estado', 'completado')->count();
    }
}
