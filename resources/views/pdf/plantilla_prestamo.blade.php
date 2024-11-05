<!DOCTYPE html>
<html>
<head>
    <title>Pagaré del Préstamo</title>
</head>
<body>
    <h1>Detalles del Préstamo</h1>
    <p><strong>Cliente:</strong> {{ $prestamo->cliente->full_name }}</p>
    <p><strong>Monto Prestado:</strong> ${{ number_format($prestamo->monto, 2) }}</p>
    <p><strong>Tasa de Interés:</strong> {{ $prestamo->tasa_interes }}%</p>

    <h2>Cuotas</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nº Cuota</th>
                <th>Fecha Vencimiento</th>
                <th>Capital</th>
                <th>Interés</th>
                <th>Total a Pagar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuotas as $cuota)
                <tr>
                    <td>{{ $cuota['numero_cuota'] }}</td>
                    <td>{{ $cuota['fecha_vencimiento'] }}</td>
                    <td>${{ number_format($cuota['capital'], 2) }}</td>
                    <td>${{ number_format($cuota['interes'], 2) }}</td>
                    <td>${{ number_format($cuota['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
