@extends('layout')

@section('resources')
{{ HTML::script('assets/own/js/gestion/inventario/index.js') }}
@stop

@section('body')

<!--Begin Datatables-->
<div id="tabla-inventarios">
    <div id="load-inventarios">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons">
                            <i class="fa fa-table"></i>
                        </div>
                        @if(count($listaInventario) == 0)
                            <h5>Inventario Vacío</h5>
                            @if(count($prodRecientes) != 0)
                                <div class="topnav">
                                    <div class="btn-group">
                                        <a data-placement="bottom" data-original-title="Registre {{ count($prodRecientes) }} {{ $aviso }}" data-toggle="tooltip" class="btn btn-default btn-sm" href="{{ URL::to('gestion/inventario/inventarioinicial') }}">
                                            <span class="label label-danger">{{ count($prodRecientes) }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @else
                            <h5>Inventario</h5>
                            @if(count($prodRecientes) != 0)
                                <div class="topnav">
                                    <div class="btn-group">
                                        <a data-placement="bottom" data-original-title="Registre {{ count($prodRecientes) }} {{ $aviso }}" data-toggle="tooltip" class="btn btn-default btn-sm" href="{{ URL::to('gestion/inventario/regprodreciente') }}">
                                            <span class="label label-danger">{{ count($prodRecientes) }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </header>
                    <div id="mensaje"></div>
                    <div id="collapse4" class="body">
                        <table id="data_table_inventario" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Producto</th>
                                    <th>Detalle</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="inventario">
                                @foreach($listaInventario as $i => $inventario)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $inventario->NombreProducto }}</td>
                                        <td>{{ $inventario->DetallesProducto }}</td>
                                        <td>{{ $inventario->stock }}</td>
                                        <td>
                                            @if($inventario->stock > 0)
                                                <a href="#ver_detalle_inv" data-toggle="modal" class="btn btn-default dt-inv" data-id="{{ md5($inventario->idProducto) }}" data-orden="1">
                                                    <i id="icon_ver_cli_7" class="fa fa-eye"></i>
                                                </a>
                                            @else
                                                <span class="label label-danger">Sin stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div>
</div>
<!--End Datatables-->

<div id="ver_detalle_inv" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="title-det">Nombre Producto</h4>
            </div>
            <div id="mensaje_inicio" class="bg-blue" style="margin: 5px 0 0 0; padding:10px 10px 10px 10px;">
                <button type="button" class="close" id="ocultar_mens_ini" title="ocultar">
                    <i class="glyphicon glyphicon-resize-small"></i>
                </button>
                Puede modificar los precios dando doble click sobre cada precio
            </div>
            <center><div id="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <div id="div_tb_inventario"></div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="agrupar_detalle_inv" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="title-det-group">Nombre Producto</h4>
            </div>
            {{ Form::open(['url' => 'gestion/inventario/agrupar-existencia', 'class' => 'form-horizontal', 'id' => 'frm_agrupar_exist']) }}
                <div class="modal-body">
                    <div id="div-1" class="body">
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Costo</label>
                            <div class="col-lg-8">
                                {{ Form::number('txt_prec_costo', '', ['id' => 'txt_prec_costo','required', 'class' => 'form-control', 'min' => 0.1, 'max' => 99999.99, 'step' => 0.01]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Distribuidor</label>
                            <div class="col-lg-8">
                                {{ Form::number('txt_prec_dist', '', ['id' => 'txt_prec_dist','required', 'class' => 'form-control', 'min' => 0.1, 'max' => 99999.99, 'step' => 0.01]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Público</label>
                            <div class="col-lg-8">
                                {{ Form::number('txt_prec_pub', '', ['id' => 'txt_prec_pub','required', 'class' => 'form-control', 'min' => 0.1, 'max' => 99999.99, 'step' => 0.01]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions no-margin-bottom">
                        {{ Form::hidden('hd_id', '', ['id' => 'hd_id'])}}
                        {{ Form::submit('Agrupar', ['id' => 'btn_agrupar','class' => 'btn btn-primary']) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<span id="ur" name="{{ URL::to('gestion/inventario') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

@stop
