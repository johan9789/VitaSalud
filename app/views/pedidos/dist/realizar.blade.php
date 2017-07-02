@extends('layout')

@section('body')

<?php
Form::macro('labelXD', function($name, $value, $id){
    return '<input type="text" name="'.$name.'"    id="lbl_'.$id.'" value="'.$value.'" style="background: transparent; border: 0 none; width: 50px;" readonly="readonly">';
});                
Form::macro('numero', function($name, $id, $index, $todprod){
    return '<input name="'.$name.'" type="number" onkeyup="prec_cantidad('.$id.', '.$todprod.');" onkeydown="prec_cantidad('.$id.', '.$todprod.');" onclick="prec_cantidad('.$id.', '.$todprod.');" class="'.$name.'" id="num_'.$id.'" min="0" max="100" tabindex="'.$index.'">';
});
$i = 1;
$j = 1;
$k = 1;
$m = 1;
date_default_timezone_set('America/Lima');
Form::macro('date', function($name){
    return '<input type="date" name="'.$name.'" required="required" value="'.date('Y-m-d').'" style="width: 200px;" class="form-control">';
});
?>

<!--Begin Datatables-->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <header>
                <div class="icons">                
                    <i class="fa fa-table"></i>
                </div>
                <h5>Lista de Productos | Realizar pedidos</h5>
                <div class="toolbar">
              </div>
            </header>
            <div id="collapse4" class="body">
                {{ Form::open(['url' => 'pedidos/dist/realizar-ya', 'class' => 'form-horizontal', 'id' => 'form_ped_prod']) }}
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr><th colspan="6">Fecha de pedido: {{ date('d/m/Y') }}</th></tr>
                        <tr>
                            <th>Producto</th>
                            <th>Prec. Dist.</th>
                            <th>Prec. Pub.</th>
                            <th>Categoria</th>
                            <th align="center">Cantidad</th>
                            <th align="center">Total</th>
                        </tr>
                        <tbody id="productos">
                            @foreach($productos as $prod)
                            <tr>
                                <td>
                                    {{ $prod->NombreProducto }}
                                    {{ Form::hidden('prod_prod[]', $prod->idProducto) }}
                                </td>
                                <td>S/. {{ Form::labelXD('prec_dist[]', number_format($prod->PrecDistribuidor, 2), $m++) }}</td>
                                <td>S/. {{ number_format($prod->PrecPublico, 2) }}</td>
                                <td>{{ $prod->NombreCategoriaProducto }}</td>
                                <td>{{ Form::numero('cantidad[]', $k++, $i++, $totalproductos) }}</td>
                                <td>{{ Form::labelXD('total_parcial[]', 0, 'total_parcial_'.$j++) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                            	<td colspan="4">          
                                    <table>
                                        <tr>
                                            <td><b>Fecha de entrega:</b></td>
                                            <td>&nbsp;</td>
                                            <td>{{ Form::date('fecha_entrega') }} </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>{{ Form::button('Realizar', ['id' => 'btn_realizar_pedidos', 'class' => 'btn btn-default btn-sm']) }}</td>
                                <th>S/.{{ Form::labelXD('total_total', 0, 'total_total') }}</th>
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
{{ HTML::script('assets/own/js/resources/jquery-1.8.3.js') }}
{{ HTML::script('assets/own/js/pedidos/general.js') }}
@stop