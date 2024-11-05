<table class="table-auto w-full text-left">
    <thead>
        <tr>
            <th class="px-4 py-2">Nº Cuota</th>
            <th class="px-4 py-2">Fecha de Vencimiento</th>
            <th class="px-4 py-2">Capital</th>
            <th class="px-4 py-2">Interés</th>
            <th class="px-4 py-2">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cuotas as $index => $cuota)
            <tr>
                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                <td class="border px-4 py-2">{{ $cuota['fecha_vencimiento'] }}</td>
                <td class="border px-4 py-2">{{ $cuota['capital'] }}</td>
                <td class="border px-4 py-2">{{ $cuota['interes'] }}</td>
                <td class="border px-4 py-2">{{ $cuota['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
