<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoDeDeudaResource\Pages;
use App\Filament\Resources\EstadoDeDeudaResource\RelationManagers;
use App\Models\EstadoDeDeuda;
use App\Models\Prestamo;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstadoDeDeudaResource extends Resource
{
    protected static ?string $model = EstadoDeDeuda::class;

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
                Tables\Columns\TextColumn::make('cliente.numero_documento')->label('Número de Documento'),
                Tables\Columns\TextColumn::make('fecha_pago')->label('Fecha de Pago a Vencer'),
                Tables\Columns\TextColumn::make('monto')->label('Monto'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->enum(['pendiente' => 'Pendiente', 'resuelto' => 'Resuelto']),
            ])
            ->filters([
                TextFilter::make('nombre')->label('Nombre'),
                TextFilter::make('apellido')->label('Apellido'),
                DateRangeFilter::make('fecha_pago')->label('Fecha de Pago'),
                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'resuelto' => 'Resuelto',
                    ])->label('Estado'),
            ])
            ->actions([
                Tables\Actions\Action::make('export_pdf')
                    ->label('Exportar PDF')
                    ->action(function () {
                        $data = Prestamo::query()->with('cliente')->get();
                        $pdf = Pdf::loadView('pdf.estado-de-deuda', ['data' => $data]);
                        return response()->streamDownload(fn() => print($pdf->output()), 'estado_de_deuda.pdf');
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
            'index' => Pages\ListEstadoDeDeudas::route('/'),
            'create' => Pages\CreateEstadoDeDeuda::route('/create'),
            'edit' => Pages\EditEstadoDeDeuda::route('/{record}/edit'),
        ];
    }
}
