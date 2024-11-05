<?php

namespace App\Providers;

use App\Filament\Resources\IngresosResource;
use App\Filament\Resources\EstadoDeudaResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Models\Prestamo;
use App\Observers\PagoObserver;


class FilamentServiceProvider extends ServiceProvider
{

    public function boot()
{
    Prestamo::observe(PagoObserver::class);
}

  /*   public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Informe'
            ]);
    
            Filament::registerNavigationItems([
                NavigationItem::make('Informe')
                    ->icon('heroicon-o-chart-bar')
                    ->group('Informe')
                    ->items([
                        NavigationItem::make('Estado de Deuda')
                            ->url('/admin/estado-deuda')
                            ->icon('heroicon-o-document'),
                        NavigationItem::make('Ingresos')
                            ->url('/admin/ingresos')
                            ->icon('heroicon-o-currency-dollar'),
                    ]),
            ]);
        });
    } */
}
