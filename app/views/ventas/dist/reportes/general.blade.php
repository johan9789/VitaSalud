<table border="1">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Tipo</th>
            <th>Sub Total</th>
            <th>Monto IGV</th>
            <th>Total</th>
            <th>Correlativo</th>
            <th>Cliente</th>
        </tr>
    </thead>
    <tbody id="">
        @foreach($lista_reportes as $rep)
        <tr>
            <td>{{ Date::format($rep->Fecha, 'd/m/Y') }}</td>
            <td>{{ Date::format($rep->Fecha, 'g:i:s A') }}</td>
            <td>@if($rep->Tipo == 'B') Boleta @else Factura @endif</td>
            <td>{{ $rep->Sub_Total }}</td>
            <td>{{ $rep->Monto_IGV }}</td>
            <td>{{ $rep->Total }}</td>
            <td>{{ $rep->Correlativo }}</td>
            <?php $cliente = ''; ?>
            <td>
                @if(is_null($val_tipo_cliente($rep->idCliente)->idEmpresa))         
                {{ $cliente = $clientes_XD($rep->idCliente)->Nombres.' '.$clientes_XD($rep->idCliente)->Apellidos }}
                @elseif(is_null($val_tipo_cliente($rep->idCliente)->idPersona))
                {{ $cliente = $empresas_XD($rep->idCliente)->NombreEmpresa }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>