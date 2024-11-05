{{--  <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
</head>
<body>
    <h1>Lista de Clientes</h1>
    <table>
        <thead>
            <tr>
                <th>CC</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Telefono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->numero_documento }}</td>
                    <td>{{ $cliente->nombres }}</td>
                    <td>{{ $cliente->apellidos }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->telefono }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
  --}}





  
  <!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lista de Clientes</title>
      <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: -100px;
            color: #333;
        }
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 100px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: left;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -20px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f9;
            color: #0056b3;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .total-count {
            margin-top: 20px;
            font-weight: bold;
            color: #0056b3;
        }
    </style>
  </head>
  <body>
  
      <h1>Lista de Clientes</h1>
      
      <div class="table-container">
          <table>
              <thead>
                  <tr>
                      <th>#</th>
                      <th>CC</th>
                      <th>Nombres</th>
                      <th>Apellidos</th>
                      <th>Email</th>
                      <th>Teléfono</th>
                  </tr>
              </thead>
              <tbody>
                  {{-- @foreach($clientes as $cliente) --}}
                  @foreach($clientes as $index => $cliente)

                      <tr>
                        <td>{{ $index + 1 }}</td> <!-- Numeración de clientes -->
                          <td>{{ $cliente->numero_documento }}</td>
                          <td>{{ $cliente->nombres }}</td>
                          <td>{{ $cliente->apellidos }}</td>
                          <td>{{ $cliente->email }}</td>
                          <td>{{ $cliente->telefono }}</td>
                      </tr>
                  @endforeach
                
              </tbody>
          </table>
          <p class="total-count">Total de clientes: {{ $clientes->count() }}</p> <!-- Cantidad total de clientes -->

      </div>
  
  </body>
  </html>
  