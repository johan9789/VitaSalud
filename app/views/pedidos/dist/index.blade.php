@extends('layout')

@section('body')

<div id="tb_ped_conf">
    <div id="tb_ped_conf_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Pedidos Realizados</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" id="btn_act_list" class="btn btn-default btn-sm" href="#" onclick="actualizar_listas();">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Actualizar Listas
                                </a>
                            </nav>
                        </div>
                    </header>
                    <div id="collapse4" class="body">
                    	@if(!$pedidos_confirmados->count())
                    	<h5>No hay Pedidos Realizados</h5>
                    	@else
                        <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                            <tr>
                                <th>Fecha de pedido</th>
                                <th>Fecha de entrega</th>
                                <th>Valor total</th>
                                <th>Opción</th>
                            </tr>
                            <tbody id="productos">
                                @foreach($pedidos_confirmados as $ped_conf)
                                <tr>                                
                                    <td>{{ date('d/m/Y', strtotime($ped_conf->FechaPedido)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($ped_conf->FechaEntrega)) }}</td>
                                    <td>{{ number_format($ped_conf->ValorTotalPedido, 2) }}</td>
                                    <td>
                                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#detalle_pedido" onclick="detalle_pedido({{ $ped_conf->idPedido }}, {{$ped_conf->EstadoPedido}});">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            Ver Detalle
                                        </a>
                                        @if($ped_conf->EstadoPedido == 3 || $ped_conf->EstadoPedido == 11)
                                            @if($ped_conf->AgregadoInvDist == 'si')
                                            <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="ocultar_pedido({{ $ped_conf->idPedido }});">
                                            <i class="glyphicon glyphicon-remove-circle"></i>
                                            Ocultar
                                            </a>
                                            @endif
                                            @if($ped_conf->AgregadoInvDist != 'si')
                                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="agregar_inventario({{ $ped_conf->idPedido }});">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                                Agregar a Inventario
                                                </a>
                                            @endif    
                                            <span class="label label-success">Confirmado</span>
                                        @else
                                            <span class="label label-warning">En Proceso</span>
                                        @endif
                                        <a href="#" id="preload_{{$ped_conf->idPedido}}"></a>
                                    </td>
                                </tr>
                                @endforeach                            
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="tb_ped_pen">
    <div id="tb_ped_pen_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Pedidos en Borrador</h5>
                        <div class="toolbar"></div>
                    </header>
                    <div id="collapse4" class="body">        
                    	@if(!$pedidos_pendientes->count())    
                    	<h5>No hay pedidos pendientes</h5>
                    	@else
                        <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                            <tr>
                                <th>Fecha de pedido</th>
                                <th>Fecha de entrega</th>
                                <th>Valor total</th>
                                <th>Opción</th>
                            </tr>                        
                            <tbody id="productos">
                                @foreach($pedidos_pendientes as $ped_pen)
                                <tr>                                
                                    <td>{{ date('d/m/Y', strtotime($ped_pen->FechaPedido)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($ped_pen->FechaEntrega)) }}</td>
                                    <td>{{ number_format($ped_pen->ValorTotalPedido, 2) }}</td>
                                    <td>
                                    @if($ped_pen->EstadoPedido == '4')
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#detalle_pedido_rechazado" onclick="detalle_pedido({{ $ped_pen->idPedido }}, {{$ped_pen->EstadoPedido}});">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            Ver Detalle
                                        </a>
                                    @else
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#detalle_pedido" onclick="detalle_pedido({{ $ped_pen->idPedido }}, {{$ped_pen->EstadoPedido}});">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            Ver Detalle
                                        </a>
                                    @endif                                
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="confirmar_pendiente({{ $ped_pen->idPedido }});">
                                            <i class="glyphicon glyphicon-ok"></i>
                                            Confirmar
                                        </a>
                                        <a class="btn btn-default btn-sm" href="{{ URL::to('pedidos/dist/modificar/'.$ped_pen->idPedido) }}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                            Modificar
                                        </a>
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="eliminar_pendiente({{ $ped_pen->idPedido }});">
                                            <i class="glyphicon glyphicon-trash"></i>
                                            Eliminar
                                        </a>
                                        @if($ped_pen->EstadoPedido == 4)
                                        <span class="label label-danger">Rechazado</span>
                                        @endif           
                                    </td>
                                </tr>
                                @endforeach                            
                            </tbody>
                        </table>                
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- #detalle_pedido:modal -->
<div id="detalle_pedido" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle pedido</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Valor</th>
                        </tr>
                        <tbody id="pedidos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- #detalle_pedido:modal -->
<div id="detalle_pedido_rechazado" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle pedido Rechado</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Valor</th>
                            <th>Disponible</th>
                        </tr>
                        <tbody id="pedidos_rechazados"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{ Form::hidden('', Request::url(), ['id' => 'url_actual']) }}
{{ Form::hidden('', URL::to(''), ['id' => 'url_general']) }}
{{ Form::hidden('', URL::to('pedidos/dist/ver-detalle'), ['id' => 'url_det_ped']) }}
{{ Form::hidden('', URL::to('pedidos/dist/confirmar-pendiente'), ['id' => 'url_conf_ped_pen']) }}
{{ Form::hidden('', URL::to('pedidos/dist/eliminar-pendiente'), ['id' => 'url_el_ped_pen']) }}
{{ Form::hidden('', URL::to('pedidos/dist/ocultar-pedido'), ['id' => 'url_oc_ped_conf']) }}
{{ Form::hidden('', URL::to('pedidos/dist/agregar-inventario'), ['id' => 'url_agregar_inv']) }}

@stop

@section('resources')
{{ HTML::script('assets/own/js/pedidos/dist/index.js') }}
@stop