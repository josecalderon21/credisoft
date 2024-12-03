<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;

Route::get('/login', function () {
    return view('auth.login'); // Asegúrate de tener esta vista.
})->name('login');

Route::get('/export-users-pdf', function () {
    $users = User::all();
    
    $pdf = Pdf::loadView('pdf.users', compact('users'));
    return $pdf->download('users-list.pdf');
})->name('export.users.pdf');



Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');




Route::get('/exportar-clientes-pdf', [ClienteController::class, 'exportarListaClientesPDF'])->name('exportar.clientes.pdf');
Route::get('/exportar-cliente-pdf/{cliente}', [ClienteController::class, 'exportarClientePDF'])->name('exportar.cliente.pdf');
 
Route::get('/exportar-prestamos-pdf', [PrestamoController::class, 'exportarPdf'])->name('exportar.prestamo.pdf');



//Route::resource('pagos', PagoController::class);

Route::post('/pago/cuota', [PagoController::class, 'registrarPago'])->name('pago.cuota');



Route::get('/', function () {
    return view('welcome');
});
