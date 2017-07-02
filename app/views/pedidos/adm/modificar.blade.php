@extends('layout')

@section('body')

<?php
Form::macro('numberII', function($name, $value, $id, $index, $todprod){
    return '<input name="'.$name.'" value="'.$value.'" type="number" onkeyup="prec_cantidad('.$id.', '.$todprod.');" onkeydown="prec_cantidad('.$id.', '.$todprod.');" onclick="prec_cantidad('.$id.', '.$todprod.');" class="'.$name.'" id="num_'.$id.'" min="0" max="100" tabindex="'.$index.'">';
});
Form::macro('labelXD', function($name, $value, $id){
    return '<input type="text" name="'.$name.'"    id="lbl_'.$id.'" value="'.$value.'" style="background: transparent; border: 0 none; width: 50px;" readonly="readonly">';
});                
$i = 1;
Form::macro('date', function($name, $value){
    return '<input type="date" name="'.$name.'" required="required" value="'.$value.'" class="form-control">';
});
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <header>
                <div class="icons">                
                    <i class="fa fa-table"></i>
                </div>
                <h5>Lista de Productos | Modificar pedido</h5>
                <div class="toolbar">
              </div>
            </header>
            <div id="collapse4" class="body">
                {{ Form::open(['url' => 'pedidos/adm/modificar-ya', 'class' => 'form-horizontal', 'id' => 'form_mod_ped']) }}
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th colspan="6">
                                <table>
                                    <tr>
                                        <td>Fecha de pedido:</td>
                                        <td>&nbsp;</td>
                                        <td>{{ Form::date('fecha_pedido', $ped->FechaPedido) }}</td>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                        <tr>
                            <th>Producto</th>
                            <th>Prec. Dist.</th>
                            <th>Prec. Pub.</th>
                            <th>Categoria</th>
                            <th align="center">Cantidad</th>
                            <th align="center">Total</th>
                        </tr>
                        <tbody id="productos">
                            @foreach($ped_prod as $pd)
                            <tr>
                                <td>
                                    {{ $pd->NombreProducto }}
                                    {{ Form::hidden('prod_prod[]', $pd->idProducto) }}
                                </td>
                                <td>S/. {{ Form::labelXD('prec_dist[]', $pd->PrecioDistribuidor, $pd->idProducto) }}</td>
                                <td>S/. {{ $pd->PrecioPublico }}</td>
                                <td>{{ $pd->NombreCategoriaProducto }}</td>
                                
                                <td>{{ Form::numberII('cantidad[]', $pd->Cantidad, $pd->idProducto, $i++, $totalproductos) }}</td>

                                <td>{{ Form::labelXD('total_parcial[]', $pd->Valor, 'total_parcial_'.$pd->idProducto) }}</td>
                            </tr>
                            @endforeach
                            @foreach($prod_sob as $ps)
                            <tr>
                                <td>
                                    {{ $ps->NombreProducto }}
                                    {{ Form::hidden('prod_prod[]', $ps->idProducto) }}
                                </td>
                                <td>S/. {{ Form::labelXD('prec_dist[]', $ps->PrecioDistribuidor, $ps->idProducto) }}</td>
                                <td>S/. {{ $ps->PrecioPublico }}</td>
                                <td>{{ $ps->NombreCategoriaProducto }}</td>
                                <td>{{ Form::numberII('cantidad[]', '', $ps->idProducto, $i++, $totalproductos) }}</td>
                                <td>{{ Form::labelXD('total_parcial[]', 0, 'total_parcial_'.$ps->idProducto) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                            	<td colspan="4">
                                    <table>
                                        <tr>
                                            <td><b>Fecha de entrega:</b></td>
                                            <td>&nbsp;</td>
                                            <td>{{ Form::date('fecha_entrega', $ped->FechaEntrega) }} </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{ Form::button('Modificar', ['id' => 'btn_modificar_pedidos', 'class' => 'btn btn-default btn-sm']) }}</td>
                                <th>S/.{{ Form::labelXD('total_total', $ped->ValorTotalPedido, 'total_total') }}</th>
                        	</tr>
                        </tbody>
                    </table>
                {{ Form::close() }}                               
            </div>
        </div>
    </div>
</div>

@stop

@section('resources')
<script type="text/javascript"  src="http://code.jquery.com/jquery-1.8.3.js"></script>
{{ HTML::script('assets/own/js/pedidos/general.js') }}
@stop