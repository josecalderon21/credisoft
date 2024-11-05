<?php

namespace App\Filament\Resources\CajaResource\Pages;

use App\Filament\Resources\CajaResource;
use App\Models\Caja;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;



class ListCajas extends ListRecords
{
    protected static string $resource = CajaResource::class;



   /*  protected function getActions(): array
    {
        return [
            Actions\Action::make('refuerzo')
                ->label('Reforzar Caja')
                ->modalHeading('Refuerzo de Caja')
                ->action('refuerzo')
                ->form([
                    Forms\Components\TextInput::make('capital_anterior')
                        ->label('Capital Anterior')
                        ->disabled()
                        ->default($this->getCapitalActual()),

                    Forms\Components\TextInput::make('capital_final')
                        ->label('Capital Final')
                        ->numeric()
                        ->required(),
                ]),
        ];
    } */

   /*  protected function reforzarCaja($data)
    {
        // Lógica para actualizar el refuerzo de caja
        $capitalFinal = $data['capital_final'];

        Caja::create([
            'capital_inicial' => $this->getCapitalActual(),
            'refuerzo' => $capitalFinal - $this->getCapitalActual(),
            'capital_final' => $capitalFinal,
        ]);

        // Actualiza los widgets y la tabla
        $this->notify('success', 'Refuerzo de caja realizado correctamente');
    }




    protected function getCapitalActual()
    {
        return Caja::sum('capital_inicial') + Caja::sum('refuerzo') - Caja::sum('capital_prestado');
    }
    */

    protected function getHeaderWidgets(): array
    {
        return [
            CajaResource\Widgets\StatsOverview::class,
        ];

    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    } 
}
