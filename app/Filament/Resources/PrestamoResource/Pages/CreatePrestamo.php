<?php

namespace App\Filament\Resources\PrestamoResource\Pages;

use App\Filament\Resources\PrestamoResource;
use App\Models\Cliente;
use App\Models\Prestamo;
use App\Http\Controllers\PrestamoController; // Importa el controlador
use App\Models\Cuota;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Exception;
use Illuminate\Support\Facades\Storage;
use Filament\Forms;
use Filament\Notifications\Notification;


class CreatePrestamo extends CreateRecord
{
    protected static string $resource = PrestamoResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('confirmCreate')
                ->label('Confirmar Creación')
                ->modalHeading('Detalles del Préstamo')
                ->modalSubheading('Verifica los detalles antes de confirmar.')
                ->modalButton('Finalizar')
                ->visible(fn() => $this->clienteTienePrestamoActivo() === false)
                ->form([
                    // Información del cliente
                    Forms\Components\TextInput::make('cliente')
                        ->label('Cliente')
                        ->default(fn() => Cliente::find($this->form->getState()['cliente_id'])->full_name ?? 'Desconocido')
                        ->disabled(),

                    Forms\Components\TextInput::make('numero_pagare')
                        ->label('Nº Pagaré')
                        ->default('100001') // Esto debe ser dinámico
                        ->disabled(),

                    Forms\Components\TextInput::make('fecha')
                        ->label('Fecha')
                        ->default(Carbon::now()->toDateString())
                        ->disabled(),

                    Forms\Components\TextInput::make('hora')
                        ->label('Hora')
                        ->default(Carbon::now()->toTimeString())
                        ->disabled(),

                    Forms\Components\TextInput::make('monto')
                        ->label('Monto Prestado')
                        ->default(fn() => $this->form->getState()['monto'] ?? 0)
                        ->disabled(),

                    // Mostrar tabla de cuotas generadas
                    Forms\Components\ViewField::make('tabla_cuotas')
                        ->label('Cuotas Generadas')
                        ->view('components.tabla-cuotas', [
                            'cuotas' => PrestamoResource::generarCuotas(
                                $this->form->getState()['monto'],
                                $this->form->getState()['tasa_interes'],
                                $this->form->getState()['numero_cuotas'],
                                $this->form->getState()['tipo_cuota']
                            ),
                        ]),
                ])
                ->action(function (array $data) {
                    // Obtener los datos del formulario
                    $prestamoData = $this->form->getState();



                    /*    // Verificar si el cliente tiene un préstamo activo
                     $prestamosActivos = Prestamo::where('cliente_id', $prestamoData['cliente_id'])
                     ->where('estado', 'activo')
                     ->count();

                 if ($prestamosActivos > 0) {
                     // Notificar en lugar de lanzar una excepción
                     Notification::make()
                         ->title('Advertencia')
                         ->body('El cliente tiene un préstamo activo que debe finalizar antes de solicitar uno nuevo.')
                         ->warning()
                         ->send();

                     return;
                 } */


                    // Calcular los intereses generados y el monto total usando la función del controlador
                    $datosCalculados = PrestamoController::calcularInteresesYMontos(
                        $prestamoData['monto'],
                        $prestamoData['tasa_interes']
                    );

                    // Añadir los campos calculados
                    $prestamoData['intereses_generados'] = $datosCalculados['intereses_generados'];
                    $prestamoData['monto_total'] = $datosCalculados['monto_total'];

                    // Calcular el valor de la cuota
                    $prestamoData['valor_cuota'] = round($prestamoData['monto_total'] / $prestamoData['numero_cuotas'], 2); // Ajusta según sea necesario

                    // Crear el registro del préstamo
                    $prestamo = Prestamo::create($prestamoData);

                    // Generar las cuotas para el préstamo
                    $cuotas = PrestamoResource::generarCuotas(
                        $prestamoData['monto'],
                        $prestamoData['tasa_interes'],
                        $prestamoData['numero_cuotas'],
                        $prestamoData['tipo_cuota']
                    );
                    // Guardar cada cuota en la base de datos
                    foreach ($cuotas as $cuotaData) {
                        $cuotaData['prestamo_id'] = $prestamo->id;
                        Cuota::create($cuotaData);
                    }

                    // Generar el PDF sin plantilla específica
                    $pdf = FacadePdf::loadView('pdf.plantilla_prestamo', [
                        'prestamo' => $prestamo,
                        'cuotas' => $cuotas,
                    ]);

                    // Guardar el PDF en el almacenamiento con el numero de documento

                    // primero se Obtiene el número de documento del cliente
                    $numeroDocumento = $prestamo->cliente->numero_documento;

                    $pdfPath = 'prestamos/' . $numeroDocumento . '/pagare.pdf';
                    Storage::disk('public')->put($pdfPath, $pdf->output());

                    // Guardar la ruta del PDF en el campo 'pdf' del préstamo
                    $prestamo->update(['pdf' => $pdfPath]);


                    // Notificación de éxito al finalizar la creación
                    Notification::make()
                        ->title('Préstamo Creado')
                        ->body('El préstamo se ha creado exitosamente.')
                        ->success()
                        ->send();
                }),
        ];
    }
    // Función auxiliar para verificar si el cliente tiene un préstamo activo
    protected function clienteTienePrestamoActivo(): bool
    {
        $clienteId = $this->form->getState()['cliente_id'] ?? null;

        if ($clienteId) {
            $prestamosActivos = Prestamo::where('cliente_id', $clienteId)
                ->where('estado', 'activo')
                ->exists();

            if ($prestamosActivos) {
                // Notificar que el cliente tiene un préstamo activo
                Notification::make()
                    ->title('Advertencia')
                    ->body('El cliente tiene un préstamo activo que debe finalizar antes de solicitar uno nuevo.')
                    ->warning()
                    ->send();

                return true;
            }
        }
        return false;
    }
}
