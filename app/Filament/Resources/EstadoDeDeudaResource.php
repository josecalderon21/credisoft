<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoDeDeudaResource\Pages;
use App\Models\Prestamo;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class EstadoDeDeudaResource extends Resource
{
    protected static ?string $model = Prestamo::class;
    protected static ?string $navigationLabel = 'Estado De Deudas';
    protected static ?string $modelLabel = 'Estado De Deudas';

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Informes';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {

        return $table->columns([
            Tables\Columns\TextColumn::make('cliente.numero_documento')
                ->label('CC')
                ->searchable(),
            Tables\Columns\TextColumn::make('cliente.nombres')
                ->label('Nombres')
                ->searchable(),
            Tables\Columns\TextColumn::make('cliente.apellidos')
                ->label('Apellidos')
                ->searchable(),
            Tables\Columns\TextColumn::make('monto_total')
                ->label('Deuda Inicial')->prefix('$')
                ->formatStateUsing(fn($state) => number_format($state, 0, '', '.')),

            BadgeColumn::make('saldo_pendiente')
                ->label('Deuda Actual')
                ->prefix('$')
                ->formatStateUsing(fn($state) => number_format($state, 0, '', '.'))
                ->badge(fn($record) => $record->saldo_pendiente > 0 ? 'Deuda Pendiente' : 'Deuda Pagada') // Texto que se mostrará en el badge
                ->color(fn($record) => $record->saldo_pendiente > 0 ? 'danger' : 'success'), // Color rojo si mayor a 0, verde si igual a 0
        ])

            ->filters([/* Aquí puedes agregar filtros si es necesario */])
            ->actions([/* Aquí puedes agregar las acciones si es necesario */])
        ;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstadoDeDeudas::route('/'),
        ];
    }

    /**
     * Método para procesar el estado de deuda.
     */
    public function abrirEstadoDeDeuda($record)
    {
        // Aquí defines la lógica para manejar los datos de las cuotas
        // Puedes retornar los datos o procesarlos según tu necesidad.
    }
}
