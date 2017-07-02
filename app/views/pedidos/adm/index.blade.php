@extends('layout')

@section('body')
<div id="tb_ped_conf">
    <div id="tb_ped_conf_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons">                
                            <i class="fa fa-table"></i>
                        </div>
                        <h5>Pedidos Entregados</h5>
                        <div class="toolbar">
                      </div>
                    </header>
                    <div id="collapse4" class="body">
                    	@if(!$pedidos_confirmados->count())
                    	<h5>No hay pedidos Entregados</h5>
                    	@else
                        <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                            <tr>
                                <th>Distribuidor</th>
                                <th>Fecha de pedido</th>
                                <th>Fecha de entrega</th>
                                <th>Valor total</th>
                                <th>Opción</th>
                            </tr>
                            <tbody id="productos">
                                @foreach($pedidos_confirmados as $ped_conf)
                                <?php $dist   = $ped_conf->Apellidos.', '.$ped_conf->Nombres;
                                      $fechaI = date('d/m/Y', strtotime($ped_conf->FechaPedido));
                                      $fechaF = date('d/m/Y', strtotime($ped_conf->FechaEntrega));
                                 ?>
                                <tr>       
                                    <td>{{ $dist }}</td>
                                    <td>{{ $fechaI }}</td>
                                    <td>{{ $fechaF }}</td>
                                    <td>{{ number_format($ped_conf->ValorTotalPedido, 2) }}</td>
                                    <td>
                                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#detalle_pedido_confirmado" onclick="detalle_pedido_confirmado({{ $ped_conf->idPedido }},'{{ $dist }}', '{{ $fechaI }}', '{{ $fechaF }}');">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            Ver Detalle
                                        </a>
                                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="ocultar_pedido({{ $ped_conf->idPedido }});">
                                            <i class="glyphicon glyphicon-remove-circle"></i>
                                            Ocultar
                                            </a>
                                        <span class="label label-success">Confirmado</span>
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
                        <div class="icons">                
                            <i class="fa fa-table"></i>
                        </div>
                        <h5>Pedidos Pendientes</h5>
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
                    	@if(!$pedidos_pendientes->count())    
                    	<h5>No hay pedidos pendientes</h5>
                    	@else
                        <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                            <tr>
                                <th>Distribuidor</th>
                                <th>Fecha de pedido</th>
                                <th>Fecha de entrega</th>
                                <th>Valor total</th>
                                <th>Opción</th>
                            </tr>                        
                            <tbody id="productos">
                                @foreach($pedidos_pendientes as $ped_pen)
                                <?php $dist   = $ped_pen->Apellidos.', '.$ped_pen->Nombres;
                                      $fechaI = date('d/m/Y', strtotime($ped_pen->FechaPedido));
                                      $fechaF = date('d/m/Y', strtotime($ped_pen->FechaEntrega));
                                 ?>
                                <tr>                                
                                    <td>{{ $dist }}</td>
                                    <td>{{ $fechaI }}</td>
                                    <td>{{ $fechaF }}</td>
                                    <td>{{ number_format($ped_pen->ValorTotalPedido, 2) }}</td>
                                    <td>
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#detalle_pedido" onclick="detalle_pedido({{ $ped_pen->idPedido }}, 'detalle','{{ $dist }}', '{{ $fechaI }}', '{{ $fechaF }}' );">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            Ver Detalle
                                        </a>
                                        <a class="btn btn-default btn-sm" href="{{ URL::to('pedidos/adm/modificar/'.$ped_pen->idPedido) }}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                            Modificar
                                        </a>
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#rechazar_pedido" onclick="detalle_pedido({{ $ped_pen->idPedido }}, 'rechazar','{{ $dist }}', '{{ $fechaI }}', '{{ $fechaF }}');">
                                            <i class="glyphicon glyphicon-retweet"></i>
                                            Rebotar
                                        </a>
                                        <a data-toggle="modal" data-placement="bottom" class="btn btn-default btn-sm" href="#" onclick="confirmar_pendiente({{ $ped_pen->idPedido }})">
                                            <i class="glyphicon glyphicon-ok"></i>
                                            Confirmar
                                        </a>
                                        <span class="label label-warning">En Proceso</span>                                
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
                <h4 class="modal-title">Detalle Pedido</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <h5 style="float:left"><strong>Distribuidor: </strong><span class="nomDist">Distribuidor</span></h5><h5 style="float:right;"><strong>Pedido </strong><span class="label label-info fechaI">Fecha Inicio</span>&nbsp;<strong>Entrega </strong><span class="label label-info fechaF">Fecha Fin</span></h5>
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Valor</th>
                            <th>Stock</th>
                        </tr>
                        <tbody id="pedidos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal - fin - #detalle_pedido:modal -->

<!-- #rechazar_pedido:modal -->
<div id="rechazar_pedido" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Rebotar pedido</h4>
            </div>
            <center><div class="loader"></div></center>
            {{ Form::open(['url' => 'pedidos/adm/rechazar-ya', 'class' => 'form-horizontal']) }}
            <div class="modal-body">
                <div id="div-1" class="body">
                    <h5 style="float:left"><strong>Distribuidor: </strong><span class="nomDist">Distribuidor</span></h5><h5 style="float:right;"><strong>Pedido </strong><span class="label label-info fechaI">Fecha Inicio</span>&nbsp;<strong>Entrega </strong><span class="label label-info fechaF">Fecha Fin</span></h5>
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Valor</th>
                            <th>Stock</th>
                            <th>Disponible</th>
                        </tr>
                        <tbody id="pedidos_rechazados"></tbody>
                    </table>

                    {{Form::hidden('id_pedido', '', ['id' => 'id_pedido'])}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-actions no-margin-bottom">
                    {{ Form::submit('Rebotar', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal - fin - #detalle_pedido:modal -->

<!-- #detalle_pedido:modal -->
<div id="detalle_pedido_confirmado" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle pedido</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <h5 style="float:left"><strong>Distribuidor: </strong><span class="nomDist">Distribuidor</span></h5><h5 style="float:right;"><strong>Pedido </strong><span class="label label-info fechaI">Fecha Inicio</span>&nbsp;<strong>Entrega </strong><span class="label label-info fechaF">Fecha Fin</span></h5>
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Valor</th>
                        </tr>
                        <tbody id="pedidos_confirmados"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal - fin - #detalle_pedido:modal -->

{{ Form::hidden('', Request::url(), ['id' => 'url_actual']) }}
{{ Form::hidden('', URL::to(''), ['id' => 'url_general']) }}
{{ Form::hidden('', URL::to('pedidos/adm/ver-detalle'), ['id' => 'url_det_ped']) }}
{{ Form::hidden('', URL::to('pedidos/adm/confirmar-pendiente'), ['id' => 'url_conf_ped_pen']) }}
{{ Form::hidden('', URL::to('pedidos/adm/ocultar-pedido'), ['id' => 'url_oc_ped_conf']) }}

@stop

@section('resources')
{{ HTML::script('assets/own/js/pedidos/adm/index.js') }}
@stop