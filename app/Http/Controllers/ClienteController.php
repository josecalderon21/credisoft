<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ClienteController extends Controller
{
   

 public function show(Cliente $cliente)
 {
     return view('clientes.show', compact('cliente'));
 }
 

    // Método para exportar la lista de clientes a PDF
    public function exportarListaClientesPDF()
    {
        $clientes = Cliente::all();
        $pdf = Pdf::loadView('pdf.clientes_lista', compact('clientes'));
        
        return $pdf->download('clientes_lista.pdf');
    }

    // Método para exportar la información de un cliente específico con su codeudor
    public function exportarClientePDF(Cliente $cliente)
    {
        $pdf = Pdf::loadView('pdf.cliente_detalle', compact('cliente'));

        return $pdf->download('cliente_' . $cliente->numero_documento . '.pdf');
    }
}
