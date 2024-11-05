<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cliente;
use App\Models\Cuota;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    // Función para mostrar el formulario de creación
    public function create()
    {
        $clientes = Cliente::all(); // Obtener todos los clientes
        return view('prestamos.create', compact('clientes'));
    }

    // Función para almacenar el préstamo
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tasa_interes' => 'required|numeric|min:0',
            'numero_cuotas' => 'required|integer|min:1',
            'tipo_cuota' => 'required|in:anual,semestral,mensual,quincenal,diario',
            'monto' => 'required|numeric|min:0',
        ]);

        // Calcular los intereses y el monto total
        $datos_calculados = $this->calcularInteresesYMontos(
            $request->monto,
            $request->tasa_interes
        );

        // Crear el préstamo con los datos calculados
        $prestamo = Prestamo::create([
            'cliente_id' => $request->cliente_id,
            'tasa_interes' => $request->tasa_interes,
            'numero_cuotas' => $request->numero_cuotas,
            'tipo_cuota' => $request->tipo_cuota,
            'monto' => $request->monto,
            'intereses_generados' => $datos_calculados['intereses_generados'],
            'monto_total' => $datos_calculados['monto_total'],
        ]);

        // Generar cuotas automáticamente después de crear el préstamo
        $cuotas = $this->generarCuotas(
            $prestamo->monto,
            $prestamo->tasa_interes,
            $prestamo->numero_cuotas,
            $prestamo->tipo_cuota
        );

        // Guardar cada cuota en la base de datos
        foreach ($cuotas as $cuotaData) {
            Cuota::create([
                'prestamo_id' => $prestamo->id,
                'numero_cuota' => $cuotaData['numero_cuota'],
                'fecha_vencimiento' => $cuotaData['fecha_vencimiento'],
                'capital' => $cuotaData['capital'],
                'interes' => $cuotaData['interes'],
                'total' => $cuotaData['total'],
                'estado' => 'pendiente',
            ]);
        }

        return redirect()->route('prestamos.index')->with('success', 'Préstamo y cuotas generados con éxito');
    }

    // Función para calcular intereses y montos
    public static function calcularInteresesYMontos($monto, $tasaInteres)
    {
        $tasaInteresDecimal = $tasaInteres / 100;

        // Calcula los intereses generados (fórmula básica de interés simple)
        $interesesGenerados = $monto * $tasaInteresDecimal;

        // Calcula el monto total a pagar
        $montoTotal = $monto + $interesesGenerados;

        return [
            'intereses_generados' => round($interesesGenerados, 2),
            'monto_total' => round($montoTotal, 2),
        ];
    }

    // Método para generar cuotas basado en el tipo de cuota
    public function generarCuotas($monto, $tasa_interes, $numero_cuotas, $tipo_cuota)
    {
        $cuotas = [];
        $fechaInicial = Carbon::now();

        for ($i = 1; $i <= $numero_cuotas; $i++) {
            switch ($tipo_cuota) {
                case 'anual':
                    $fechaVencimiento = $fechaInicial->copy()->addYears($i);
                    break;
                case 'semestral':
                    $fechaVencimiento = $fechaInicial->copy()->addMonths($i * 6);
                    break;
                case 'mensual':
                    $fechaVencimiento = $fechaInicial->copy()->addMonths($i);
                    break;
                case 'quincenal':
                    $fechaVencimiento = $fechaInicial->copy()->addWeeks($i * 2);
                    break;
                case 'diario':
                    $fechaVencimiento = $fechaInicial->copy()->addDays($i);
                    break;
                default:
                    throw new \InvalidArgumentException("Tipo de cuota no válido: $tipo_cuota");
            }

            $capital = $monto / $numero_cuotas;
            $interes = ($capital * $tasa_interes) / 100;
            $total = $capital + $interes;

            $cuotas[] = [
                'numero_cuota' => $i,
                'fecha_vencimiento' => $fechaVencimiento->toDateString(),
                'capital' => $capital,
                'interes' => $interes,
                'total' => $total,
            ];
        }

        return $cuotas;
    }

    // Función para exportar PDF
    public function exportarPdf()
    {
        // Obtener todos los préstamos con información del cliente
        $prestamos = Prestamo::with('cliente')->get();

        // Renderizar la vista con los datos
        $pdf = Pdf::loadView('pdf.prestamos', compact('prestamos'));

        // Devolver el PDF como descarga o mostrarlo en el navegador
        return $pdf->stream('lista-prestamos.pdf');
    }
}
