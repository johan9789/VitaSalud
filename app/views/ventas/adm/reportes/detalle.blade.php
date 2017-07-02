<table style="width: 100%;">
    <tr>
        <th>Fecha:</th>
        <td>{{ (string)$fecha_fecha }}</td>
    </tr>
    <tr>
        <th>Hora:</th>
        <td>{{ (string)$fecha_hora }}</td>
    </tr>
    <?php $id_cliente = $venta_detalle[0]->idCliente; ?>
    <tr>
        <th>Cliente:</th>
        <td>
            @if(is_null($val_tipo_cliente($id_cliente)->idEmpresa))         
            {{ $clientes_XD($id_cliente)->Nombres.' '.$clientes_XD($id_cliente)->Apellidos }}
            @elseif(is_null($val_tipo_cliente($id_cliente)->idPersona))
            {{ $empresas_XD($id_cliente)->NombreEmpresa }}
            @endif
        </td>
    </tr>
</table>
<table border="1" style="width: 100%;">
    <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Total</th>
    </tr>
    <tbody>
        @foreach($venta_detalle as $v)
        <tr>
            <td>{{ $v->NombreProducto }}</td>
            <td>{{ $v->PrecioUnit }}</td>
            <td>{{ $v->Cantidad }}</td>
            <td>{{ $v->PrecioTotal }}</td>
        </tr>
        @endforeach
    </tbody>
</table>