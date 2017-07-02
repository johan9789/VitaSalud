@extends('layout')

@section('body')

<!--Tabla: Lista de Usuarios-->
<div id="tb_us">
    <div id="tb_us_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Lista de usuarios registrados</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nuevo_usuario">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nuevo Usuario
                                </a>
                            </nav>
                        </div>
                    </header>
                    <div id="collapse4" class="body">
                        <table id="data_table_usuarios" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Apellidos y Nombres</th>
                                    <th>DNI</th>                        
                                    <th>Celular</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Tipo de Usuario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personas as $per)
                                <tr>
                                    <td>{{{ $per->nombre_completo }}}</td>
                                    <td>{{{ $per->DNI }}}</td>                        
                                    <td>{{{ $per->Celular }}}</td>
                                    <td>{{{ $per->email }}}</td>
                                    <td>{{{ $per->Usuario }}}</td>
                                    <td>{{{ $per->nombretipo }}}</td>
                                    <td>
                                        <a href="#editar_usuario" class="btn btn-primary btn-sm" onclick="editar_usuario('{{{ $per->idPersona }}}', '{{{ $per->id_tipousuario }}}', '{{{ $per->iddistrito }}}');" data-toggle="modal" title="Editar usuario">
                                            <li class="fa fa-pencil"></li>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="eliminar_usuario('{{{ $per->idPersona }}}')" data-toggle="modal" title="Eliminar usuario">
                                            <li class="fa fa-trash"></li>
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
<!--Fin - Tabla: Lista de Usuarios-->

<!-- #nuevo_usuario:modal -->
<div id="nuevo_usuario" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar usuarios</h4>
            </div>
            <div class="modal-body">
                <div class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'gestion/usuarios/registrar', 'class' => 'form-horizontal', 'id' => 'form_reg_us']) }}
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('nombre', '', ['required', 'class' => 'form-control', 'autofocus']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('apellidos', 'Apellidos', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('apellidos', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('dni', 'D.N.I.', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('dni', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('telefono', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('celular', 'Celular', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('celular', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::email('email', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('direccion', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('distrito', 'Distrito', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('distrito', $distritos, '', ['class' => 'form-control', 'required']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('usuario', 'Usuario', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('usuario', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('contrasena', 'Contraseña', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::password('contrasena', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('tipo_usuario', 'Tipo de usuario', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('tipo_usuario', $tipo_usuarios, '', ['class' => 'form-control chzn-select', 'tabindex' => '2', 'id' => 'tipo_usuario']) }}
                            </div>
                        </div>
                        <div class="form-group" id="hide_form_group" style="display: none;">
                            {{ Form::label('rango_distribuidor', 'Rango', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::select('rango_distribuidor', $rango_distribuidor, '', ['class' => 'form-control chzn-select', 'tabindex' => '2', 'id' => 'rango_distribuidor']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Registrar', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#nuevo_usuario:modal -->

<!-- #editar_usuario:modal -->
<div id="editar_usuario" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span>Editar usuarios</span>
                    <span>{{ HTML::image('assets/img/ajax.gif', '', ['id' => 'gif_editar_usuario']) }}</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'gestion/usuarios/actualizar', 'class' => 'form-horizontal', 'id' => 'form_editar_usuario']) }}    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Nombre</label>
                            <div class="col-lg-8">{{ Form::text('nombre', '', ['required', 'class' => 'form-control', 'autofocus', 'id' => 'ed_us_nombre']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Apellidos</label>
                            <div class="col-lg-8">{{ Form::text('apellidos', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_aps']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">D.N.I.</label>
                            <div class="col-lg-8">{{ Form::text('dni', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_dni']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Teléfono</label>
                            <div class="col-lg-8">{{ Form::text('telefono', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_telefono']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Celular</label>
                            <div class="col-lg-8">{{ Form::text('celular', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_celular']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">E-mail</label>
                            <div class="col-lg-8">{{ Form::email('email', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_email']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Dirección</label>
                            <div class="col-lg-8">{{ Form::text('direccion', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_direccion']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Distrito</label>
                            <div class="col-lg-8">{{ Form::select('distrito', [], '', ['required', 'class' => 'form-control', 'id' => 'select_us_distrito']) }}</div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Usuario</label>
                            <div class="col-lg-8">{{ Form::text('usuario', '', ['required', 'class' => 'form-control', 'id' => 'ed_us_usuario']) }}</div>
                        </div>                        
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Tipo de Usuario</label>
                            <div class="col-lg-8">{{ Form::select('tipo_usuario', [], '', ['class' => 'form-control chzn-select', 'tabindex' => '2', 'id' => 'select_ed_usuario']) }}</div>
                        </div>                        
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Actualizar', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#editar_usuario:modal -->

@stop

@section('resources')

<script type="text/javascript">
var distritos = {
@foreach($distritos as $id => $value)
    {{ $id }} : '{{ $value }}',
@endforeach
};

var tipos_usuario = {
@foreach($tipo_usuarios as $id => $value)
    {{ $id }} : '{{ $value }}',
@endforeach
};
</script>

{{ HTML::script('assets/own/js/gestion/usuarios/index.js') }}
@stop