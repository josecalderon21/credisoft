<?php
// app/Models/Ingreso.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pago;

class Ingresos extends Model
{
    protected $fillable = [
        'id_ingreso',
        'monto',
        'ganancia',
    ];

    // Evitar que el modelo intente buscar una tabla
    protected $table = null;

    public static function calcularPorFecha($fecha)
    {
        $pagos = Pago::whereDate('created_at', $fecha)->get();

        $montoTotal = $pagos->sum('monto_abonado'); // Suma el monto abonado
        $ganancia = $pagos->reduce(function ($totalGanancia, $pago) {
            $prestamo = $pago->prestamo;
            $interesDiario = $prestamo->intereses_generados / $prestamo->numero_cuotas;
            return $totalGanancia + $interesDiario;
        }, 0);

        return [
            'monto' => $montoTotal,
            'ganancia' => $ganancia,
        ];
    }
}
