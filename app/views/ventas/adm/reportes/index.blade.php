@extends('layout')

@section('body')

<div>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-shopping-cart"></i></div>
                        <h5>Reporte de ventas en general</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" id="btn_desc_rep_general" class="btn btn-danger btn-sm" href="{{ URL::to('ventas/adm/reportes/general') }}">
                                    <i class="fa fa-download"></i>
                                    Descargar reporte general
                                </a>
                                <a data-toggle="modal" id="btn_actualizar_reportes" class="btn btn-primary btn-sm" href="#">
                                    <i class="fa fa-spinner"></i>
                                    Actualizar
                                </a>
                            </nav>
                        </div>
                    </header>    
                    <div class="body">
                        <table>
                            <tr>
                                <th>Fecha:</th>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>{{ Form::input('date', '', $fecha, ['class' => 'form-control', 'id' => 'date_venta']) }}</td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <th>Rango:</th>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>{{ Form::text('dates', $fechas, ['class' => 'form-control', 'id' => 'reservation']) }}</td>
                                <td>&nbsp;</td>
                                <td>{{ Form::button('Ver', ['class' => 'btn btn-default', 'id' => 'btn_int_fechas']) }}</td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><b>Distribuidor:</b></td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                    <select data-placeholder="Seleccione Distribuidor" class="form-control chzn-select" tabindex="-1" id="select_dist">
                                        @foreach($lista_distribuidores as $dist)                                        
                                        <option value="{{ $dist->idDistribuidor }}">{{ $dist->Nombres.' '.$dist->Apellidos }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>                            
                        </table>                        
                    </div>              
                    <div id="collapse4" class="body">
                        <div id="dv_rp_ventas">
                            <div id="dv_rep_ventas_act">
                                <table id="data_table_ventas" class="table table-bordered table-condensed table-hover table-striped">
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
                                            <th>Opción</th>
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
                                            <td>
                                                <a class="btn btn-default btn-sm" onclick="detalle_venta('{{ $rep->idventa }}', '{{ Date::format($rep->Fecha, 'd/m/Y') }}', '{{ Date::format($rep->Fecha, 'g:i:s A') }}', '{{ $cliente }}');" data-toggle="modal" href="#detalle_venta">
                                                    <li class="fa fa-retweet"></li> Ver Detalle
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="detalle_venta" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle de venta</h4>
                <span id="dtd_venta"></span>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <table class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                        <tbody id="tbd_detalle_venta"></tbody>
                    </table>
                    <a class="btn btn-primary" id="link_impr_ven" target="_blank">
                        <li class="fa fa-download"></li> Imprimir
                    </a>
                    <a class="btn btn-danger" id="link_des_ven" target="">
                        <li class="fa fa-download"></li> Descargar como PDF
                    </a>
                    <a class="btn btn-success" id="link_des_exc_ven" target="">
                        <li class="fa fa-download"></li> Descargar como Excel
                    </a>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

@stop

@section('resources')
{{ HTML::script('assets/own/js/ventas/adm/reportes/index.js') }}
<script>
$(function(){
    Metis.formGeneral();
});
</script>
@stop