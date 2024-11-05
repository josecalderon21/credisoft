<!-- resources/views/pdf/prestamos.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Lista de Préstamos</h1>
    <table>
        <thead>
            <tr>
                <th>CC</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Monto</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->cliente->numero_documento }}</td>
                    <td>{{ $prestamo->cliente->nombres }}</td>
                    <td>{{ $prestamo->cliente->apellidos }}</td>
                    <td>{{ $prestamo->monto }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
