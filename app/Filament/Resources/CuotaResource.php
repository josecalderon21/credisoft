<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CuotaResource\Pages;
use App\Filament\Resources\CuotaResource\RelationManagers;
use App\Models\Cuota;
use App\Models\Prestamo;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

class CuotaResource extends Resource
{
    protected static ?string $model = Cuota::class;
    protected static ?string $navigationGroup = 'Informes';

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('prestamo_id')
                    ->label('Préstamo')
                    ->options(Prestamo::all()->pluck('id', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('numero_cuota')
                    ->label('Número de Cuota')
                    ->numeric()
                    ->required(),

                DatePicker::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    ->required(),

                TextInput::make('capital')
                    ->label('Capital')
                    ->numeric()
                    ->required(),

                TextInput::make('interes')
                    ->label('Interés')
                    ->numeric()
                    ->required(),

                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->required(),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada' => 'Pagada',
                    ])
                    ->default('pendiente')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('prestamo.cliente.nombres')
                    ->label('Nombres')
                    ->searchable(),

                TextColumn::make('prestamo.cliente.apellidos')
                    ->label('Apellidos')
                    ->searchable(),

                TextColumn::make('numero_cuota')
                    ->label('Número de Cuota')
                    ->sortable(),

                TextColumn::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    ->date()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Valor')
                    ->prefix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 0, '', '.')),
                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'danger' => 'pendiente',
                        'success' => 'pagada',
                    ]),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada' => 'Pagada',
                    ]),

            ])
            ->defaultSort('fecha_vencimiento', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCuotas::route('/'),
            //'create' => PagoResource\Pages\CreatePago::route('/create'),
            //'edit' => Pages\EditCuota::route('/{record}/edit'),
        ];
    }
}
