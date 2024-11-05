<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoResource\Pages;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->options(Cliente::all()->pluck('full_name', 'id'))
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
                            } else {
                                $set('monto_total', 'Sin deuda');
                            }
                        }
                    }),

                TextInput::make('numero_documento')
                    ->label('Número de Identificación')
                    ->disabled(),

                TextInput::make('monto_total')
                    ->label('Deuda Total')
                    ->disabled(),

                
                    Select::make('cuota_id')
                    ->label('Cuota a Pagar')
                    ->options(function (callable $get) {
                        $clienteId = $get('cliente_id');
                        if (!$clienteId) return [];
                
                        $prestamo = Prestamo::where('cliente_id', $clienteId)->latest()->first();
                        if ($prestamo) {
                            // Filtrar solo las cuotas pendientes del préstamo
                            return $prestamo->cuotas()
                                ->where('estado', 'pendiente')
                                ->pluck('numero_cuota', 'id');
                        }
                        return [];
                    })
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $clienteId = $get('cliente_id');
                        if ($clienteId) {
                            $prestamo = Prestamo::where('cliente_id', $clienteId)->latest()->first();
                            if ($prestamo) {
                                // Guardar el id del préstamo seleccionado
                                $set('prestamo_id', $prestamo->id);
                            }
                        }
                    }),
                
                    Select::make('prestamo_id')
                    ->label('N* del Préstamo')
                    ->options(function (callable $get) {
                        $clienteId = $get('cliente_id');
                        if (!$clienteId) return [];
                
                        return Prestamo::where('cliente_id', $clienteId)->pluck('id', 'id');
                    })
                    ->reactive()
                    ->required()
                    ->hidden(fn(callable $get) => !$get('cliente_id')), // Mostrar solo si se selecciona un cliente
                
                
                    

                // Campo tipo de pago
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
                                    // Monto de una cuota
                                    $set('monto_abonado', $prestamo->valor_cuota);
                                } elseif ($tipoPago === 'total') {
                                    // Monto total pendiente
                                    $saldoPendiente = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
                                    $set('monto_abonado', $saldoPendiente);
                                } else {
                                    // Limpiar para el monto personalizado
                                    $set('monto_abonado', null);
                                }

                                // Actualizar el saldo pendiente basado en el monto abonado
                                $montoAbonado = $get('monto_abonado') ?? 0;
                                $saldoPendiente = ($prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado')) - $montoAbonado;
                                $set('saldo_pendiente', max($saldoPendiente, 0)); // Evitar valores negativos
                            }
                        }
                    }),

                // Campo monto abonado
                TextInput::make('monto_abonado')
                    ->label('Valor a Abonar')
                    ->numeric()
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
                    ->dehydrated(), // Asegura que el valor se envíe al guardarlo




                TextInput::make('saldo_pendiente')
                    ->label('Saldo Pendiente')
                    ->default(fn(callable $get) => $get('monto_total') - $get('monto_abonado'))
                    ->reactive()
                    ->afterStateHydrated(function (callable $get, callable $set) {
                        $montoAbonado = $get('monto_abonado');
                        $deudaTotal = $get('monto_total');
                        $saldoPendiente = $deudaTotal - $montoAbonado;
                        $set('saldo_pendiente', $saldoPendiente >= 0 ? $saldoPendiente : 0);
                    }),

                Radio::make('modalidad_pago')
                    ->label('Modalidad de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                    ])
                    ->reactive()
                    ->required(),

                TextInput::make('numero_comprobante')
                    ->label('Número de Comprobante')
                    ->required(fn(callable $get) => $get('modalidad_pago') === 'transferencia')
                    ->hidden(fn(callable $get) => $get('modalidad_pago') !== 'transferencia'),
            ]);
    }

    protected static function booted()
    {
        static::creating(function (Pago $pago) {
            $prestamo = Prestamo::where('cliente_id', $pago->cliente_id)->latest()->first();
    
            if (!$prestamo) {
                throw new \Exception('El préstamo seleccionado no existe.');
            }
    
            // Calcular el saldo pendiente después de aplicar el monto abonado
            $deudaTotal = $prestamo->monto_total - $prestamo->pagos()->sum('monto_abonado');
            $saldoPendiente = $deudaTotal - $pago->monto_abonado;
    
            // Validar si el monto abonado es mayor a la deuda total
            if ($pago->monto_abonado > $deudaTotal) {
                throw new \Exception('El monto abonado no puede ser mayor que la deuda total.');
            }
    
            // Actualizar el saldo pendiente y el préstamo
            $pago->saldo_pendiente = max($saldoPendiente, 0);
            $prestamo->saldo_pendiente = $pago->saldo_pendiente;
            $prestamo->save();
    
            // Si se seleccionó el tipo de pago "total"
            if ($pago->tipo_pago === 'total') {
                // Marcar todas las cuotas como "pagadas"
                $prestamo->cuotas()->where('estado', 'pendiente')->update(['estado' => 'pagada']);
            } else if ($pago->tipo_pago === 'cuota') {
                // Marcar solo la cuota seleccionada como "pagada"
                $cuota = $prestamo->cuotas()->where('id', $pago->cuota_id)->first();
                if ($cuota) {
                    $cuota->estado = 'pagada';
                    $cuota->save();
                }
            }
        });
    }
    



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.numero_documento')->label('CC')->searchable(),
                TextColumn::make('cliente.nombres')->label('Nombres')->searchable(),
                TextColumn::make('cliente.apellidos')->label('Apellidos')->searchable(),
                TextColumn::make('monto_abonado')->label('Monto Abonado')->money('COP'),
                TextColumn::make('tipo_pago')->label('Tipo de Pago'),
                TextColumn::make('modalidad_pago')->label('Modalidad de Pago'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'edit' => Pages\EditPago::route('/{record}/edit'),
        ];
    }
}

