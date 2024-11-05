{{-- <!DOCTYPE html>
<html>
<head>
    <title>Información del Cliente</title>
</head>
<body>
    <h1>Información del Cliente</h1>
    <p><strong>Nombres:</strong> {{ $cliente->nombres }}</p>
    <p><strong>Apellidos:</strong> {{ $cliente->apellidos }}</p>
    <p><strong>Tipo de Documento:</strong> {{ $cliente->tipo_documento }}</p>
    <p><strong>Número de Documento:</strong> {{ $cliente->numero_documento }}</p>
    <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
    <p><strong>Ciudad:</strong> {{ $cliente->ciudad }}</p>
    <p><strong>Email:</strong> {{ $cliente->email }}</p>

    <h2>Información del Codeudor</h2>
    <p><strong>Nombres:</strong> {{ $cliente->codeudor_nombres }}</p>
    <p><strong>Apellidos:</strong> {{ $cliente->codeudor_apellidos }}</p>
    <p><strong>Tipo de Documento:</strong> {{ $cliente->codeudor_tipo_documento }}</p>
    <p><strong>Número de Documento:</strong> {{ $cliente->codeudor_numero_documento }}</p>
    <p><strong>Teléfono:</strong> {{ $cliente->codeudor_telefono }}</p>
    <p><strong>Ciudad:</strong> {{ $cliente->codeudor_ciudad }}</p>
    <p><strong>Email:</strong> {{ $cliente->codeudor_email }}</p>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: left;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            color: #0056b3;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-section p {
            margin: 8px 0;
            line-height: 1.6;
        }
        .info-section strong {
            width: 200px;
            display: inline-block;
            color: #000;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Información del Cliente</h1>

        <div class="info-section">
            <div class="info-row">
                <p><strong>Nombres:</strong> {{ $cliente->nombres }}</p>
                <p><strong>Apellidos:</strong> {{ $cliente->apellidos }}</p>
            </div>
            <div class="info-row">
                <p><strong>Tipo de Documento:</strong> {{ $cliente->tipo_documento }}</p>
                <p><strong>Número de Documento:</strong> {{ $cliente->numero_documento }}</p>
            </div>
            <div class="info-row">
                <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                <p><strong>Ciudad:</strong> {{ $cliente->ciudad }}</p>
            </div>
            <div class="info-row">
                <p><strong>Email:</strong> {{ $cliente->email }}</p>
            </div>
        </div>

        <h2>Información del Codeudor</h2>

        <div class="info-section">
            <div class="info-row">
                <p><strong>Nombres:</strong> {{ $cliente->codeudor_nombres }}</p>
                <p><strong>Apellidos:</strong> {{ $cliente->codeudor_apellidos }}</p>
            </div>
            <div class="info-row">
                <p><strong>Tipo de Documento:</strong> {{ $cliente->codeudor_tipo_documento }}</p>
                <p><strong>Número de Documento:</strong> {{ $cliente->codeudor_numero_documento }}</p>
            </div>
            <div class="info-row">
                <p><strong>Teléfono:</strong> {{ $cliente->codeudor_telefono }}</p>
                <p><strong>Ciudad:</strong> {{ $cliente->codeudor_ciudad }}</p>
            </div>
            <div class="info-row">
                <p><strong>Email:</strong> {{ $cliente->codeudor_email }}</p>
            </div>
        </div>
    </div>

</body>
</html>
