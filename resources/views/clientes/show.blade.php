
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">{{ $cliente->nombres }} {{ $cliente->apellidos }}</h1>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <!-- Deuda Actual -->
                    <div class="col-md-4">
                        <div class="card bg-danger text-white text-center">
                            <div class="card-body">
                                <h2>Deuda Actual</h2>
                                <p>${{ number_format($cliente->deuda_actual, 0) }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Cuotas Pendientes -->
                    <div class="col-md-4">
                        <div class="card bg-info text-white text-center">
                            <div class="card-body">
                                <h2>Cuotas Pendientes</h2>
                                <p>{{ $cliente->cuotas_pagadas }} de {{ $cliente->total_cuotas }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Próximo Pago -->
                    <div class="col-md-4">
                        <div class="card bg-warning text-white text-center">
                            <div class="card-body">
                                <h2>Próximo Pago</h2>
                                <p>{{ $cliente->proximo_pago ? $cliente->proximo_pago->format('d M, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info-section mb-4">
                    <h2>Información del Cliente</h2>
                    <p><strong>Nombre Completo:</strong> {{ $cliente->nombres }} {{ $cliente->apellidos }}</p>
                    <p><strong>Documento:</strong> {{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</p>
                    <p><strong>Email:</strong> {{ $cliente->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                </div>
                <div class="info-section mb-4">
                    <h2>Información del Codeudor</h2>
                    <p><strong>Nombre Completo:</strong> {{ $cliente->codeudor_nombres }} {{ $cliente->codeudor_apellidos }}</p>
                    <p><strong>Documento:</strong> {{ $cliente->codeudor_tipo_documento }} {{ $cliente->codeudor_numero_documento }}</p>
                    <p><strong>Email:</strong> {{ $cliente->codeudor_email }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->codeudor_telefono }}</p>
                </div>
                <div class="table-section">
                    <h2>Pagos Realizados</h2>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Monto</th>
                                <th>Medio de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01 Jan, 2024</td>
                                <td>10:00 AM</td>
                                <td>$500,000</td>
                                <td>Transferencia</td>
                            </tr>
                            <tr>
                                <td>15 Feb, 2024</td>
                                <td>02:30 PM</td>
                                <td>$300,000</td>
                                <td>Efectivo</td>
                            </tr>
                            <tr>
                                <td>10 Mar, 2024</td>
                                <td>11:15 AM</td>
                                <td>$200,000</td>
                                <td>Tarjeta de Crédito</td>
                            </tr>
                            <tr>
                                <td>05 Abr, 2024</td>
                                <td>04:45 PM</td>
                                <td>$100,000</td>
                                <td>Cheque</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    
    
</body>
</html>

