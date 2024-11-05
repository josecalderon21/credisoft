<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresosResource\Pages;
use App\Filament\Resources\IngresosResource\RelationManagers;
use App\Models\Ingresos;
use App\Models\Pago;
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

class IngresosResource extends Resource
{
    protected static ?string $model = Ingresos::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                Tables\Columns\TextColumn::make('cliente.nombre')->label('Nombre'),
                Tables\Columns\TextColumn::make('cliente.apellido')->label('Apellido'),
                Tables\Columns\TextColumn::make('monto')->label('Monto'),
                Tables\Columns\TextColumn::make('pendiente')->label('Monto Pendiente'),
            ])
            ->filters([
            
                SelectFilter::make('tipo_pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                    ])->label('Tipo de Pago'),
            ])->default('draft')
            ->selectablePlaceholder(false)
            ->actions([
                Tables\Actions\Action::make('export_pdf')
                    ->label('Exportar PDF')
                    ->action(function () {
                        $data = Pago::query()->with('cliente')->get();
                        $pdf = Pdf::loadView('pdf.ingresos', ['data' => $data]);
                        return response()->streamDownload(fn() => print($pdf->output()), 'ingresos.pdf');
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngresos::route('/'),
            'create' => Pages\CreateIngresos::route('/create'),
            'edit' => Pages\EditIngresos::route('/{record}/edit'),
        ];
    }
}
