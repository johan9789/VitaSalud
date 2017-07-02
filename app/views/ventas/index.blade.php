@extends('layout')

@section('body')

<div class="row">
  	<div class="col-lg-12">
        <div class="box">
            <div id="dv_ventas">
              	<div id="dv_ventas_act">
                    <header>
                      	<div class="icons"><i class="fa fa-shopping-cart"></i></div>
                      	<h5>
                            <span>Productos</span> 
                            <span style="margin-left: 100px; cursor: pointer;" title="Quitar cliente">
                                <button class="btn btn-default btn-sm" id="spn_cli_selec" style="display: none;"></button>
                            </span>
                        </h5>
                      	<div style="float: right;">
                            <nav style="padding: 5px;">
                                <a class="btn btn-default btn-sm" href="#seleccionar_cliente" data-toggle="modal">
                                    <li class="fa fa-check-square-o"></li>
                                    Seleccionar cliente    
                                </a>
                                <a class="btn btn-default btn-sm" href="#seleccionar_empresa" data-toggle="modal">
                                    <li class="fa fa-check-square-o"></li>
                                    Seleccionar empresa    
                                </a>
                            </nav>
                      	</div>
                    </header>
                    <div id="collapse4" class="body">                                
                      	{{ Form::open(['url' => 'ventas', 'class' => 'form-horizontal', 'id' => 'form_ventas']) }}
                            {{ Form::hidden('cliente', '', ['id' => 'hd_cliente_seleccionado']) }}
                        	<table id="dataTable" class="table table-bordered responsive-table">
                              	<thead>
                                	<tr>
	                                	<th>Codigo</th>
	                                    <th>Producto</th>
	                                    <th>Precio</th>
	                                    <th>Cantidad</th>
	                                    <th>Precio Total</th>
                                	</tr>
                              	</thead>
                              	<tbody id="tbd_ventas">
                                	<tr id="fila_ventas_1">
                                      	<td>{{ Form::text('codigo[]', '', ['class' => 'form-control txt_codigo', 'id' => 'txt_codigo_1', 'data' => '1', 'autofocus', 'autocomplete' => 'off', 'codprod' => '']) }}</td>
                                      	<td>{{ Form::text('producto[]', '', ['class' => 'form-control', 'id' => 'txt_producto_1', 'data' => '1', 'readonly', 'style' => 'background: transparent; border: 0 none; cursor: default;']) }}</td>
                                      	<td>{{ Form::number('precio[]', '', ['class' => 'form-control', 'id' => 'txt_precio_1', 'data' => '1', 'readonly', 'style' => 'background: transparent; border: 0 none; cursor: default; width: 100px;']) }}</td>
                                      	<td style="text-align:center;">
                                            {{ Form::number('cantidad[]', '', ['class' => 'form-control txt_cantidad', 'id' => 'txt_cantidad_1', 'data' => '1', 'style' => 'width: 100px;', 'min' => 1, 'readonly']) }}
                                  		</td>
								        <td style="text-align: center;">
                                            {{ Form::number('total[]', '', ['class' => 'form-control txt_total', 'id' => 'txt_total_1', 'data' => '1', 'style' => 'width: 100px; background: transparent; border: 0 none; cursor: default;', 'readonly', 'step' => '0.01']) }}
                                        </td>
                                    </tr>
                              	</tbody>
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold;" colspan="3">Total:</td>
                                        <td style="text-align: center;">
                                            {{ Form::number('', 0, ['id' => 'cantidad_final', 'class' => 'form-control', 'style' => 'width: 100px; background: transparent; border: 0 none; cursor: default;', 'readonly']) }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ Form::number('', 0, ['id' => 'total_final', 'class' => 'form-control', 'style' => 'width: 100px; background: transparent; border: 0 none; cursor: default;', 'readonly', 'step' => '0.01']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            {{ Form::button('Cancelar venta', ['class' => 'btn btn-danger', 'id' => 'btn_cancelar_venta']) }}
                                        </td>
                                        <td>
                                            {{ Form::select('tipo', ['B' => 'Boleta', 'F' => 'Factura'], '', ['class' => 'form-control']) }}
                                        </td>
                                        <td style="text-align:right;">
                                            {{ Form::button('Finalizar venta', ['class' => 'btn btn-primary', 'id' => 'btn_finalizar_venta']) }}
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
</div>

<div id="seleccionar_cliente" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    Seleccionar cliente
                    <a href="#registrar_cliente" class="btn btn-default btn-sm" style="margin-left: 10px;" data-toggle="modal">
                        <li class="fa fa-users"></li> Registrar nuevo cliente
                    </a>
                </h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <br>
                    <div id="div_tb_clientes">
                        <div id="div_tb_clientes_act">
                            <table id="data_table_clientes" class="table table-bordered table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Apellidos</th>
                                        <th>Nombres</th>
                                        <th>DNI</th>
                                        <th>Celular</th>
                                        <th>Dirección</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($listaClientes as $cliente)
                                    <tr>
                                        <td>{{{ $cliente->Apellidos }}}</td>
                                        <td>{{{ $cliente->Nombres }}}</td>
                                        <td>{{{ $cliente->DNI }}}</td>
                                        <td>{{{ $cliente->Celular }}}</td>
                                        <td>{{{ $cliente->Direccion }}}</td>
                                        <td>
                                            <a class="btn btn-default" onclick="seleccionar_cliente('{{{ $cliente->idCliente }}}');">
                                                <li id="icon_selec_cli_{{{ $cliente->idCliente }}}" class="fa fa-check-square-o"></li>
                                            </a>
                                            <a href="#ver_cliente" data-toggle="modal" class="btn btn-default" onclick="ver_cliente('{{{ $cliente->idCliente }}}');">
                                                <li id="icon_ver_cli_{{{ $cliente->idPersona }}}" class="fa fa-eye"></li>
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

<div id="seleccionar_empresa" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    Seleccionar empresa
                    <a href="#registrar_empresa" class="btn btn-default btn-sm" style="margin-left: 10px;" data-toggle="modal">
                        <li class="fa fa-users"></li> Registrar nueva empresa
                    </a>
                </h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <br>
                    <div id="div_tb_empresas">
                        <div id="div_tb_empresas_act">
                            <table id="data_table_empresas" class="table table-bordered table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>RUC</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Distrito</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($listaEmpresas as $empresa)
                                    <tr>
                                        <td>{{{ $empresa->RUC }}}</td>
                                        <td>{{{ $empresa->NombreEmpresa }}}</td>
                                        <td>{{{ $empresa->DireccionEmpresa }}}</td>
                                        <td>{{{ $empresa->NombreDistrito }}}</td>                             
                                        <td>
                                            <a class="btn btn-default" onclick="seleccionar_empresa('{{{ $empresa->idCliente }}}');">
                                                <li id="icon_selec_emp_{{{ $empresa->idCliente }}}" class="fa fa-check-square-o"></li>
                                            </a>
                                            <a href="#ver_empresa" data-toggle="modal" class="btn btn-default" onclick="ver_empresa('{{{ $empresa->idCliente }}}');">
                                                <li id="icon_ver_emp_{{{ $empresa->idEmpresa }}}" class="fa fa-eye"></li>
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

<div id="registrar_cliente" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar cliente</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'clientes', 'class' => 'form-horizontal', 'id' => 'form_reg_cli']) }}
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('nombre', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('apellidos', 'Apellidos', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('apellidos', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('dni', 'D.N.I.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('dni', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('telefono', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('celular', 'Celular', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('celular', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email', '', ['class' => 'form-control', 'id' => 'em_cli', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('distrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('distrito', $distritos, '', ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_rg_cli']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="ver_cliente" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ver cliente</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['class' => 'form-horizontal']) }}
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_nombre', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('apellidos', 'Apellidos', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_app', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('dni', 'D.N.I.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_dni', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_tel', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('celular', 'Celular', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_cel', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_email', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_dir', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('distrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_cli_dist', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="registrar_empresa" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar empresa</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'empresas', 'class' => 'form-horizontal', 'id' => 'form_reg_emp']) }}
                        <div class="form-group">
                            {{ Form::label('ruc', 'R.U.C.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('ruc', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('nombre', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('telefono', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email', '', ['class' => 'form-control', 'id' => 'em_cli', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('distrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('distrito', $distritos, '', ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
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

<div id="ver_empresa" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ver empresa</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['class' => 'form-horizontal']) }}
                        <div class="form-group">
                            {{ Form::label('ruc', 'R.U.C.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_ruc', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_nom', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_dir', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_tel', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_email', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('distrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('', '', ['class' => 'form-control', 'id' => 'spn_ver_emp_dist', 'style' => 'background: transparent; border: 0 none; cursor: default;', 'disabled']) }}
                            </div>
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
{{ HTML::script('assets/own/js/ventas/index.js') }}
@stop