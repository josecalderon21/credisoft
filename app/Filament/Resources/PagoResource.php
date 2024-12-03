<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoResource\Pages;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Registro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cliente_id')
                    ->prefixIcon('heroicon-o-user')
                    ->label('Cliente')
                    ->placeholder('Seleccione un Cliente con una Deuda Activa')
                    ->options(
                        Cliente::whereHas('prestamos', function ($query) {
                            $query->where('estado', '!=', 'cancelado'); // Filtrar clientes con préstamos activos
                        })
                            ->get()
                            ->mapWithKeys(function ($cliente) {
                                return [$cliente->id => $cliente->full_name];
                            })
                    )

                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $cliente = Cliente::find($state);
                        if ($cliente) {
                            $set('numero_documento', $cliente->numero_documento);
                            $prestamo = $cliente->prestamos()->whereNotNull('monto_total')->latest()->first();

                            if ($prestamo) {
                                $saldoPendiente = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                                $set('monto_total', $saldoPendiente);
                                $set('prestamo_id', $prestamo->id);
                            } else {
                                $set('monto_total', 'Sin deuda');
                            }
                            $cuotaPendiente = $prestamo->cuotas()
                                ->where('estado', 'pendiente')
                                ->orderBy('fecha_vencimiento', 'asc') // Ordenar por la fecha de vencimiento más próxima
                                ->first();

                            if ($cuotaPendiente) {
                                $set('cuota_id', $cuotaPendiente->id);
                                $fechaVencimiento = $cuotaPendiente->fecha_vencimiento;

                                // Formatear con Carbon
                                $fechaFormateada = $fechaVencimiento instanceof \Carbon\Carbon
                                    ? $fechaVencimiento->format('d-m-Y') // Formato deseado
                                    : Carbon::parse($fechaVencimiento)->format('d M, Y');

                                $set('fecha_cuota_actual', $fechaFormateada);
                            } else {
                                $set('fecha_cuota_actual', 'Sin cuotas pendientes');
                            }
                        } else {
                            $set('monto_total', 'Sin deuda');
                            $set('fecha_cuota_actual', 'Sin cuotas pendientes');
                        }
                    }),

                TextInput::make('numero_documento')
                    ->label('Número de Identificación')
                    ->prefix('#')
                    ->disabled(),



                Hidden::make('prestamo_id')
                    ->default(null) // Por defecto, null hasta que se asigne un valor
                    ->required(),

                Hidden::make('cuota_id')
                    ->default(fn(callable $get) => $get('cuota_id')),

                TextInput::make('fecha_cuota_actual')
                    ->label('Fecha de la Cuota Actual')

                    ->disabled()
                    ->prefixIcon('heroicon-o-calendar'), // Asegúrate de que el valor se guarde en el modelo
                TextInput::make('monto_total')
                    ->label('Deuda Actual')
                    ->prefix('$')
                    ->disabled(),

                Radio::make('tipo_pago')
                    ->label('¿Qué valor quiere pagar?')
                    ->options([
                        'cuota' => 'Pagar Cuota',
                        'total' => 'Pagar Total',
                        'otro' => 'Pagar Otro Valor',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $tipoPago = $get('tipo_pago');
                        $clienteId = $get('cliente_id');

                        if ($clienteId) {
                            $prestamo = Prestamo::where('cliente_id', $clienteId)->latest()->first();

                            if ($prestamo) {
                                if ($tipoPago === 'cuota') {
                                    $set('monto_abonado', $prestamo->valor_cuota);
                                } elseif ($tipoPago === 'total') {
                                    $saldoPendiente = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                                    $set('monto_abonado', $saldoPendiente);
                                } else {
                                    $set('monto_abonado', null);
                                }

                                $montoAbonado = $get('monto_abonado') ?? 0;
                                $saldoPendiente = ($prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado')) - $montoAbonado;
                                $set('saldo_pendiente', max($saldoPendiente, 0));
                            }
                        }
                    }),
                Radio::make('modalidad_pago')
                    ->label('Modalidad de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                    ])
                    ->reactive()
                    ->required(),
                TextInput::make('monto_abonado')
                    ->label('Valor a Abonar')
                    ->numeric()
                    ->prefix('$')
                    ->required(fn(callable $get) => $get('tipo_pago') === 'otro')
                    ->disabled(fn(callable $get) => $get('tipo_pago') !== 'otro')
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $montoAbonado = $get('monto_abonado') ?? 0;
                        $clienteId = $get('cliente_id');

                        if ($clienteId) {
                            $prestamo = Prestamo::where('cliente_id', $clienteId)->latest()->first();

                            if ($prestamo) {
                                $deudaTotal = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                                $nuevoSaldo = $deudaTotal - $montoAbonado;

                                if ($montoAbonado > $deudaTotal) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('El monto abonado no puede ser mayor que la deuda total.')
                                        ->danger()
                                        ->send();
                                    $set('monto_abonado', null);
                                    $set('saldo_pendiente', $deudaTotal);
                                } else {
                                    $set('saldo_pendiente', max($nuevoSaldo, 0));
                                }
                            }
                        }
                    })
                    ->dehydrated(),

                TextInput::make('numero_comprobante')
                    ->label('Número de Comprobante')
                    ->required(fn(callable $get) => $get('modalidad_pago') === 'transferencia')
                    ->hidden(fn(callable $get) => $get('modalidad_pago') !== 'transferencia'),
                TextInput::make('saldo_pendiente')
                    ->label('Saldo Pendiente')
                    ->prefix('$')
                    ->default(fn(callable $get) => $get('monto_total') - $get('monto_abonado'))
                    ->disabled()
                    ->dehydrated(true)
                    ->reactive()
                    ->afterStateHydrated(function (callable $get, callable $set) {
                        $montoAbonado = $get('monto_abonado');
                        $deudaTotal = $get('monto_total');
                        $saldoPendiente = $deudaTotal - $montoAbonado;
                        $set('saldo_pendiente', $saldoPendiente >= 0 ? $saldoPendiente : 0);
                    }),
            ]);
    }

    protected static function booted()
    {
        static::creating(function (Pago $pago) {
            // Obtener el préstamo relacionado al cliente
            $prestamo = Prestamo::where('cliente_id', $pago->cliente_id)->latest()->first();

            if (!$prestamo) {
                throw new \Exception('El préstamo seleccionado no existe.');
            }

            // Calcular la deuda total pendiente
            $deudaTotal = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
            $saldoPendiente = $deudaTotal - $pago->monto_abonado;

            // Verificar que el monto abonado no sea mayor que la deuda pendiente
            if ($pago->monto_abonado > $deudaTotal) {
                throw new \Exception('El monto abonado no puede ser mayor que la deuda total.');
            }

            // Actualizar el saldo pendiente en el pago
            $pago->saldo_pendiente = max($saldoPendiente, 0);
            $prestamo->saldo_pendiente = $pago->saldo_pendiente;

            // Guardar el cambio en el préstamo
            $prestamo->save();
        });
    }






    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->sortable()
                    ->getStateUsing(fn($record) => Carbon::parse($record->created_at)->format('d M, Y')),
                TextColumn::make('cliente.nombres')->label('Nombres')->searchable(),
                TextColumn::make('cliente.apellidos')->label('Apellidos')->searchable(),
                TextColumn::make('monto_abonado')->label('Monto Abonado')->prefix('$')->formatStateUsing(fn($state) => number_format($state, 0, '', '.')),
                TextColumn::make('tipo_pago')->label('Tipo de Pago'),
                TextColumn::make('modalidad_pago')->label('Modalidad de Pago'),
            ])
            ->filters([])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePago::route('/create'),
            //'edit' => Pages\EditPago::route('/{record}/edit'),
        ];
    }
}
