<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function registrarPago(Request $request)

{


        // Encuentra la cuota con el ID especificado
        $cuota = Cuota::findOrFail($request->cuota_id);

        // Verifica si la cuota ya está pagada para evitar pagos duplicados
        if ($cuota->estado === 'pagado') {
            return response()->json([
                'message' => 'Esta cuota ya está marcada como pagada.'
            ], 400);
        }


    // Validar los datos del pago
    $request->validate([
        'cuota_id' => 'required|exists:cuotas,id',
        'monto' => 'required|numeric|min:0',
    ]);

    // Buscar la cuota correspondiente
    $cuota = Cuota::find($request->cuota_id);

    // Verificar si el monto pagado cubre el total de la cuota
    if ($request->monto >= $cuota->monto) {
        // Actualizar el estado de la cuota a "Pagada"
        $cuota->estado = 'Pagada';
        $cuota->save();
    }

    // Registrar el pago en la base de datos (puedes personalizar la lógica según tu modelo de pago)
    Pago::create([
        'cuota_id' => $cuota->id,
        'monto' => $request->monto,
        'fecha' => now(),
    ]);

    return redirect()->back()->with('success', 'El pago se ha registrado correctamente y la cuota ha sido marcada como pagada.');
}

}
 


