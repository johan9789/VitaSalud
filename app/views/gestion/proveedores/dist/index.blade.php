@extends('layout')

@section('body')

<div id="div_proveedores">
  	<div id="div_proveedores_act">
  		<div class="row">
    		<div class="col-lg-12">
  				<div class="box">
        			<header>
          				<div class="icons">
            				<i class="fa fa-table"></i>
          				</div>
          				<h5>Proveedores</h5>
          				<div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" class="btn btn-default btn-sm" href="#nuevo_proveedor">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nuevo Proveedor
                                </a>
                            </nav>
                        </div>
        			</header>
        			<div id="collapse4" class="body">
          				<table id="data_table_proveedores" class="table table-bordered table-condensed table-hover table-striped">
            				<thead>
				              	<tr>
				                	<th>Razón social</th>
					                <th>RUC</th>
					                <th>Teléfono</th>
					                <th>E-mail</th>
					                <th>Dirección</th>
                                    <th>Distrito</th>
					                <th>Opción</th>
				              	</tr>
            				</thead>
            				<tbody id="">
                				@foreach($proveedores as $prov)
                                    <tr>
                                        <td>{{ $prov->razon_social_proveedor_dist }}</td>
                                        <td>{{ $prov->RUC }}</td>
                                        <td>{{ $prov->telf_proveedor_dist }}</td>
                                        <td>{{ $prov->email_proveedor_dist }}</td>
                                        <td>{{ $prov->direccion_proveedor_dist }}</td>
                                        <td>{{ $prov->distrito->NombreDistrito }}</td>
                                        <td>
                                            <span class="editar" data-id="J">
                                                <a data-toggle="modal" class="btn btn-primary btn-sm" href="#editar_proveedor" onclick="editarProveedor('{{ $prov->id_proveedor_dist }}', '{{ $prov->distrito->iddistrito }}');">
                                                    <li class="fa fa-pencil"></li>
                                                </a>
                                            </span>
                                            <span class="editar" data-id="J">
                                                <a data-toggle="modal" class="btn btn-danger btn-sm" href="#" onclick="eliminarProveedor('{{ $prov->id_proveedor_dist }}');">
                                                    <li class="fa fa-trash"></li>
                                                </a>
                                            </span>
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

<div id="nuevo_proveedor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar proveedor</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'gestion/proveedores', 'class' => 'form-horizontal', 'id' => 'form_reg_prov']) }}
                        <div class="form-group">
                            {{ Form::label('RUC', 'R.U.C.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('RUC', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('razon_social_proveedor', 'Razón Social', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('razon_social_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion_proveedor', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono_proveedor', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('telf_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email_proveedor', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email_proveedor_dist', '', ['class' => 'form-control', 'id' => 'em_cli', 'autocomplete' => 'off']) }}
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

<div id="editar_proveedor" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    Editar proveedor
                    {{ HTML::image('assets/img/ajax.gif', '', ['id' => 'gif_editar_proveedor', 'style' => 'display: none;'])  }}
                </h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => '', 'class' => 'form-horizontal', 'id' => 'form_ed_prov']) }}
                        <div class="form-group">
                            {{ Form::label('RUC', 'R.U.C.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('RUC', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off', 'id' => 'txt_ruc']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('razon_social_proveedor_dist', 'Razón Social', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('razon_social_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off', 'id' => 'txt_razon_social']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion_proveedor', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off', 'id' => 'txt_direccion_proveedor']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telf_proveedor_dist', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::number('telf_proveedor_dist', '', ['required', 'class' => 'form-control', 'autocomplete' => 'off', 'id' => 'txt_telefono_proveedor']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email_proveedor_dist', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email_proveedor_dist', '', ['class' => 'form-control', 'id' => 'em_cli', 'autocomplete' => 'off', 'id' => 'txt_email_proveedor']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('iddistrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('iddistrito', [], '', ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'id' => 'sel_distritos']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Editar', ['class' => 'btn btn-primary']) }}
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
{{ HTML::script('assets/own/js/gestion/proveedores/dist/index.js') }}
@stop
