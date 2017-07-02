@extends('layout')

@section('body')

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <header>
                <div class="icons">                
                    <i class="fa fa-table"></i>
                </div>
                <h5>Confirmar pedido</h5>
                <div class="toolbar">
              </div>
            </header>
            <div id="collapse4" class="body">
                {{ Form::open(['url' => 'pedidos/dist/confirmar-ya', 'class' => 'form-horizontal']) }}
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr><th colspan="5">Fecha de pedido: {{ date('d/m/Y') }}</th></tr>
                        <tr>
                            <th>Producto</th>                           
                            <th>Precio</th>
                            <th>Cantidad</th>                        
                            <th>Total</th>
                        </tr>
                        <tbody id="productos">
                            @foreach($pedidos_productos as $prod)
                            <tr>
                                <td>
                                    {{ $prod->NombreProducto }}
                                    {{ Form::hidden('prod_prod[]', $prod->idProducto) }}
                                </td>
                                <td>{{ number_format($prod->PrecDistribuidor, 2) }}</td>
                                <td>{{ $prod->Cantidad }}</td>                        
                                <td>{{ number_format($prod->Valor, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" align=""></td>
                            	<th>{{ number_format($prod->ValorTotalPedido, 2) }}</th>
                        	</tr>
                        </tbody>
                    </table>
                    {{ Form::submit('Confirmar pedido', ['class' => 'btn btn-default btn-sm']) }}
                    {{ Form::button('Confirmar después', ['class' => 'btn btn-default btn-sm', 'onclick' => 'location="'.URL::to('pedidos/dist?confirmar=despues').'"']) }}
                {{ Form::close() }}                               
            </div>
        </div>
    </div>
</div>

@stop