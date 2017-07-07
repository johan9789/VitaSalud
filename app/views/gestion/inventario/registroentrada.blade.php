@extends('layout')

@section('resources')
{{ HTML::script('assets/own/js/gestion/inventario/entradas.js') }}
<script>
$(function(){
    Metis.formGeneral();
    Metis.MetisProgress();
});
</script>
@stop

@section('body')

<!--Begin Datatables-->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div id="div_entradas">
                <div id="load">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Lista de Productos registrados</h5>
                        <div class="toolbar" id="preload"></div><!-- /.toolbar -->
                        <div style="float: right;">
                            <nav style="padding: 5px;">
                                @if(Session::has('mensaje_entradas'))
                                    <div id="mensaje_entradas" style="padding: 3px 3px 3px 3px; background: #5cb85c; border-radius:6px; color: #FFF;">
                                        &nbsp;&nbsp;{{ Session::get('mensaje_entradas') }} ¿Ver Inventario?
                                        <a data-placement="bottom" data-original-title="Ir a Inventario" data-toggle="tooltip" class="btn btn-default btn-sm" href="{{URL::to('gestion/inventario')}}">
                                            <span class="label label-danger">Si</span>
                                        </a>&nbsp;&nbsp;
                                    </div>
                                @endif
                            </nav>
                        </div>
                    </header>
                    <p class="bg-danger" id="mensaje_error" style="color:#D80C0C;">
                        @if(Session::has('mensaje_error'))
                            <b>{{Session::get('mensaje_error')}}</b>
                        @endif
                    </p>
                    <div id="collapse4" class="body">
                        <?php
                        //Para crear campo numerico u otro
                        Form::macro('numberdecimal', function ($name, $id, $data_id) {
                            return '<input type="number" name="' . $name . '" class="form-control inventario precio" required min="0.01" max="999999.99" id="' . $id . '" data-id="' . $data_id . '" step="0.01" readonly>';
                        });

                        Form::macro('numberentero', function ($name, $id, $data_id) {
                            return '<input type="number" name="' . $name . '" class="form-control inventario entrada" required min="1" max="999999" id="' . $id . '" data-id="' . $data_id . '" readonly>';
                        });
                        ?>
                        {{ Form::open(['url' => 'gestion/inventario/actualizarentradas', 'class' => 'form-horizontal', 'id' => 'form_registro_entradas']) }}
                            <table id="dataTable" class="table table-bordered responsive-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Producto</th>
                                        <th>Costo</th>
                                        <th>Entrada</th>
                                        <th>Existencia Entrante</th>
                                        <th>Costo Total</th>
                                    </tr>
                                </thead>
                                <tbody id="entradas">
                                    <tr class='datos_entradas'>
                                        <td><input type="checkbox" class="chk_eliminar" name="chk_elim1" value="1" id="chk_elim1" data-id="1"></td>
                                        <td width="250px">
                                            <select data-placeholder="Seleccione Producto..." class="form-control chzn-select slc_producto" tabindex="5" name="sel_prod_inv[]" id="sel_prod_inv1" data-id="1">
                                                <option value="0"></option>
                                                @foreach($categorias as $categoria)
                                                    <optgroup label="{{ $categoria->NombreCategoriaProducto }}">
                                                        @foreach($categoria->productos as $producto)
                                                            <option value="{{ $producto->idProducto }}">{{ $producto->NombreProducto }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ Form::numberdecimal('precio[]', 'precio1', '1') }}</td>
                                        <td>{{ Form::numberentero('entrada[]', 'entrada1', '1') }}</td>
                                        <td style="text-align: center;">
                                            <label id="lbl_Existencia1" name="lbl_Existencia1" data-id="1" style="display: none;">0</label>
                                            <label id="lbl_verExistencia1">0</label>
                                        </td>
                                        <td style="text-align: right;">
                                            S/. <label id="lbl_PrecTot1" name="lbl_PrecTot1" data-id="1" style="display: none;">0</label>
                                            <label id="lbl_verPrecTot1" class="lbl_verPrecTot">0</label>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="text-align: right;">Total:</td>
                                        <td style="text-align: right;"><output id="total_inv">0</output></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="button" id="agregar_entradas" value="+" disabled="disabled">
                                            <input type="button" id="quitar_entradas" value="-" disabled="disabled">
                                        </td>
                                        <td colspan="5" style="text-align: right;">
                                            {{ Form::submit('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_registrar_entradas']) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->

<!--End Datatables-->

<span id="ur" name="{{ URL::to('gestion/inventario') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>
<span id="increment_id" data-id="1"></span>

@stop
