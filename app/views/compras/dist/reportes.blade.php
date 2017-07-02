@extends('layout')

@section('body')

<div>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-shopping-cart"></i></div>
                        <h5>Reporte de compras en general</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" id="btn_actualizar_reportes" class="btn btn-primary btn-sm" href="#">
                                    <i class="fa fa-spinner"></i>
                                    Actualizar
                                </a>
                            </nav>
                        </div>
                    </header>
                    <div id="collapse4" class="body">
                        <div id="dv_rep_compras">
                            <div id="dv_rep_compras_act">
                                <table id="data_table_compras" class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>N° Factura</th>
                                            <th>Sub Total</th>
                                            <th>Monto IGV</th>
                                            <th>Total</th>
                                            <th>Proveedor</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                        @foreach($lista_reportes as $rep)
                                        <tr>
                                            <td>{{{ Date::format($rep->FechaCompraDist, 'd/m/Y') }}}</td>
                                            <td>{{{ $rep->FacturaDist }}}</td>
                                            <td>S./ {{{ $rep->SubTotalCompraDist }}}</td>
                                            <td>S./ {{{ $rep->IGVCompraDist }}}</td>
                                            <td>S./ {{{ $rep->TotalCompraDist }}}</td>
                                            <td>{{{ $rep->proveedor->razon_social_proveedor_dist }}}</td>
                                            <td>
                                                <a class="btn btn-default btn-sm" onclick="detalle_compra('{{{ $rep->idCompraDist }}}');" data-toggle="modal" href="#detalle_compra">
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

<div id="detalle_compra" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle de compra</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <table class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Producto</th>
                            <th>Costo</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                        <tbody id="tbd_detalle_compra"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

@stop

@section('resources')
{{ HTML::script('assets/own/js/compras/dist/reportes.js') }}
@stop