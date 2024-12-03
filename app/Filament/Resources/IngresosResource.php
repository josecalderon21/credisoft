<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresosResource\Pages;
use App\Filament\Resources\IngresosResource\RelationManagers;
use App\Models\Ingresos;
use App\Models\Pago;
use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TextFilter;
use Filament\Tables\Filters\DateRangeFilter;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\DateFilter; // Importa el filtro de fechas correctamente

class IngresosResource extends Resource
{
    protected static ?string $model = Pago::class;
    protected static ?string $navigationLabel = 'Ingresos';
    protected static ?string $modelLabel = 'Ingresos';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Informes';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->searchable(),
                TextColumn::make('hora')
                    ->label('Hora')
                    ->getStateUsing(fn($record) => $record->getHora())
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('h:i A')),

                    BadgeColumn::make('monto_abonado')
                    ->label('Monto')
                    ->prefix('$')
                    ->color('success')
                    ->formatStateUsing(fn($state) => number_format($state, 0, '', '.')),
                TextColumn::make('tasa')
                    ->label('Tasa de Interes')
                    ->suffix('%')
                    ->formatStateUsing(fn($state) => number_format($state, 0, '', '.'))
                    ->getStateUsing(fn($record) => $record->getTasaInteres()),
                TextColumn::make('capital')
                    ->label('Capital')
                    ->prefix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 0, '', '.'))
                    ->getStateUsing(fn($record) => $record->getCapital()),
                TextColumn::make('gananciaPorPago')
                    ->label('Ganancia')
                    ->prefix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 0, '', '.'))
                    ->getStateUsing(fn($record) => $record->gananciaPorPago()),
            ])
            ->filters([

            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngresos::route('/'),
            //'create' => Pages\CreateIngresos::route('/create'),
            //'edit' => Pages\EditIngresos::route('/{record}/edit'),
        ];
    }
}
