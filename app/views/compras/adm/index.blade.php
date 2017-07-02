@extends('layout')

@section('body')

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <header>
            	<div class="icons"><i class="fa fa-table"></i></div>
              	<h5 id="actualizar_vista" title="Actualizar vista" style="cursor: pointer;">Registrar compras</h5>
              	<div class="toolbar" id="preload"></div>
              	<div style="float: right;">
              		<nav style="padding: 5px;">
                        <a class="btn btn-default btn-sm" href="#seleccionar_proveedor" data-toggle="modal" id="btn_seleccionar_proveedor">
                            <li class="fa fa-check-square-o"></li>
                            Seleccionar proveedor    
                        </a>
                        <span id="spn_proveedor_seleccionado" style="margin-right: 10px; font-size: 13px; font-weight: bold; cursor: pointer; display: none;" title="Quitar proveedor">
                            <i>Se seleccionó un proveedor</i>
                        </span>
                        <a class="btn btn-default btn-sm" href="{{ URL::to('gestion/productos') }}" data-toggle="modal" target="_blank">
                            <li class="fa fa-check-square-o"></li>
                            Nuevo producto
                        </a>
                    </nav>
              	</div>
            </header>   
            <div id="div_compras">
                <div id="div_compras_act">                 
                    <div id="collapse4" class="body">
                      	{{ Form::open(['url' => 'compras/adm', 'class' => 'form-horizontal', 'id' => 'form_compras']) }}
                            {{ Form::hidden('proveedor', '', ['id' => 'hd_proveedor_seleccionado']) }}
                            <table class="table table-bordered responsive-table">
                            	<thead>
                                	<tr>
	                                    <th>Producto</th>
	                                    <th>Costo</th>
	                                    <th>Cantidad</th>
	                                    <th>Precio Total</th>
                                	</tr>
                              	</thead>
                              	<tbody id="tbd_compras">
                                    <tr id="fila_compras_1" data="1">
                                      	<td width="250px">
                                            <select data-placeholder="Seleccione Producto..." class="form-control select_prod" name="sel_prod_inv[]" id="select_prod_1" data="1">
                                                <option value="0"></option>
                                        		    @foreach($categoria_productos as $cat)  
                                      			    <optgroup label="{{{ $cat->NombreCategoriaProducto }}}">
                                                        @foreach($cat->productos as $prod)
	                                          		    <option value="{{{ $prod->idProducto }}}">
                                                            {{{ $prod->NombreProducto }}}
                                                        </option>
                                                        @endforeach
                                          		    </optgroup>
                                        		    @endforeach
                                            </select>
                                      	</td>
                                      	<td>{{ Form::number('costo[]', 0.00, ['class' => 'form-control txt_costo', 'required', 'min' => 0.01, 'max' => 999999.99, 'id' => 'txt_costo_1', 'step' => 0.1, 'data' => 1, 'disabled']) }}</td>
                                      	<td>{{ Form::number('cantidad[]', 0, ['class' => 'form-control txt_cantidad', 'required', 'min' => 1, 'max' => 100000, 'id' => 'txt_cantidad_1', 'data' => 1, 'disabled']) }}</td>
                                      	<td style="text-align: center;">S/. <label id="lbl_total_1" class="lbl_total" data="1">0</label></td>
                                	  </tr>
                              	</tbody>
                              	<tbody>
                                	<tr>
	                                  	<th colspan="2" style="text-align: right;">
                                          IGV: <span id="ver_igv">0</span>
                                      </th>
	                                  	<th style="text-align:right;">SubTotal:</th>
	                                  	<th style="text-align:center;">S./ <span id="total_sub_total">0</span></th>
                                	</tr>
                                  <tr>
                                      <td colspan="2"></td>
                                      <th style="text-align:right;">Total:</th>
                                      <th style="text-align:center;">S./ <span id="total_total">0</span></th>
                                  </tr>
                            		  <tr>
                                  		<td>
                                          {{ Form::button('+', ['id' => 'btn_agregar_compras', 'class' => 'btn btn-default btn-sm', 'disabled']) }}
                                      </td>
                                      <td style="text-align: right;">{{ Form::text('factura', '', ['class' => 'form-control', 'placeholder' => 'N° Factura', 'id' => 'txt_factura']) }}</td>
                                      <td>{{ Form::input('date', 'fecha', Date::current(), ['class' => 'form-control']) }}</td>
                                  		<td colspan="3" style="text-align:right;">{{ Form::button('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_registrar_compra']) }}</td>
                                	</tr>
                              	</tbody>
                            </table>
                      	{{ Form::close() }}
                	  </div>
				        </div>
			      </div>
		    </div>
  	</div>
</div>

{{ Form::hidden('', $impuesto, ['id' => 'hd_igv']) }}

<div id="seleccionar_proveedor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    Seleccionar proveedor
                    <a href="#registrar_proveedor" target="_blank" class="btn btn-default btn-sm" style="margin-left: 10px;" data-toggle="modal">
                        <li class="fa fa-users"></li> Registrar nuevo proveedor
                    </a>
                </h4>
            </div>
            <div class="modal-body">
                <div id="div_proveedores">
                    <div id="div_proveedores_act">
                        <div id="div-1" class="body">
                            <br>
                            <table id="data_table_proveedores" class="table table-bordered table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>RUC</th>
                                        <th>Razon social</th>
                                        <th>Dirección</th>
                                        <th>Distrito</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lista_proveedores as $proveedor)
                                    <tr>
                                        <td>{{{ $proveedor->RUC }}}</td>
                                        <td>{{{ $proveedor->razon_social_proveedor }}}</td>
                                        <td>{{{ $proveedor->direccion_proveedor }}}</td>
                                        <td>{{{ $proveedor->distrito->NombreDistrito }}}</td>                             
                                        <td>
                                            <a class="btn btn-default" onclick="seleccionar_proveedor('{{{ $proveedor->id_proveedor }}}');">
                                                <li class="fa fa-check-square-o"></li>
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
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="registrar_proveedor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar proveedor</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'gestion/proveedores/adm', 'class' => 'form-horizontal', 'id' => 'form_reg_prov']) }}
                        <div class="form-group">
                            {{ Form::label('RUC', 'R.U.C.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('RUC', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('razon_social_proveedor', 'Razón Social', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('razon_social_proveedor', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion_proveedor', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion_proveedor', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono_proveedor', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('telefono_proveedor', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email_proveedor', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email_proveedor', '', ['class' => 'form-control', 'id' => 'em_cli', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('iddistrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('iddistrito', $distritos, '', ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_rg_emp']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

@stop

@section('resources')
{{ HTML::script('assets/own/js/compras/adm/index.js') }}
@stop