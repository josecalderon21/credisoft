<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteCajaResource\Pages;
use App\Models\ReporteCaja;
use App\Filament\Resources\capital_caja;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Filament\Resources\ReporteCajaResource\Widgets\StatsOverview as ReporteCajaStatsOverview;
use App\Models\Capital;
use App\Models\RefuerzoCapital;  // Asegúrate de importar el modelo de RefuerzoCapital
use Filament\Notifications\Notification;


class ReporteCajaResource extends Resource
{
    protected static ?string $model = ReporteCaja::class;
    protected static ?string $navigationLabel = 'Caja'; // Etiqueta visible en el menú
    //protected static ?string $navigationGroup = 'Administrativo';
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;
    // Método que actualiza la visibilidad del botón de crear basado en la existencia de 1 registros
    public static function canCreate(): bool
    {
        // Si ya existe un registro, no se podrá crear uno nuevo
        return Capital::count() === 0;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('monto')
                ->label('Capital Inicial')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(RefuerzoCapital::query())  // Establecemos que la tabla mostrará los registros de RefuerzoCapital
        ->columns([
            Tables\Columns\TextColumn::make('created_at')  // Muestra la fecha del refuerzo
                ->label('Fecha de Refuerzo')
                ->getStateUsing(fn($record) => Carbon::parse($record->created_at)->format('d M, Y - H:i')),

            Tables\Columns\TextColumn::make('monto_refuerzo')  // Muestra el monto del refuerzo
                ->label('Monto de Refuerzo')
                ->prefix('$')
                ->formatStateUsing(fn($state) => number_format(floatval($state), 0, ',', '.')),  // Formatea el monto con separador de miles
        ])
        ->headerActions([  // Acción para agregar un nuevo refuerzo
            Tables\Actions\Action::make('refuerzo')
                ->label('Refuerzo')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->modalHeading('Refuerzo de Caja')
                ->form([
                    Forms\Components\TextInput::make('monto_refuerzo')
                        ->label('Monto de Refuerzo')
                        ->numeric()
                        ->required()
                        ->prefix('$'),
                ])
                ->action(function (array $data) {
                    $montoRefuerzo = $data['monto_refuerzo'];
         // Actualizar el capital principal
         $capital = Capital::first();
         if (!$capital) {
             // Crear un registro inicial si no existe
             $capital = Capital::create(['monto' => 0]);
         }
         $capital->increment('monto', $montoRefuerzo);
                    // Paso 1: Obtener el último registro de ReporteCaja para actualizar su monto
                    $reporteCaja = ReporteCaja::latest()->first();  // Tomamos el último ReporteCaja
                    $reporteCaja->monto += $montoRefuerzo;  // Sumamos el monto de refuerzo al monto del ReporteCaja
                    $reporteCaja->save();  // Guardamos los cambios

                    // Paso 2: Registrar el refuerzo en la tabla 'refuerzo_capital'
                    RefuerzoCapital::create([
                        'monto_refuerzo' => $montoRefuerzo,  // Asegúrate de pasar el monto
                        'fecha' => now(),
                        'reporte_caja_id' => $reporteCaja->id,  // Asociamos el refuerzo al ReporteCaja
                    ]);

                    // Paso 3: Enviar notificación de éxito
                    Notification::make()
                        ->title('Refuerzo registrado')
                        ->success()
                        ->body("Se ha incrementado el capital en \${$montoRefuerzo}.")
                        ->send();
                })
        ])
        ->filters([/* Agrega filtros si es necesario */])
        ->actions([
           // Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReporteCajas::route('/'),
           // 'create' => Pages\CreateReporteCaja::route('/create'),
            //'edit' => Pages\EditReporteCaja::route('/{record}/edit'),
        ];
    }

    
}
