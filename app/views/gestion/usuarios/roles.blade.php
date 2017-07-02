@extends('layout')

@section('body')

<div id="tb_rol">
    <div id="tb_rol_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Lista de roles</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nuevo_rol">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nuevo Rol
                                </a>
                            </nav>
                        </div>
                    </header>
                    <div id="collapse4" class="body">
                        <table id="data_table_roles" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @foreach($lista_roles as $roles)
                                <tr>
                                    <td>{{{ $roles->id_tipousuario }}}</td>
                                    <td>{{{ $roles->nombretipo }}}</td>
                                    <td>{{{ $roles->descripcion_tipousuario }}}</td>
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


<div id="nuevo_rol" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar rol</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body" style="margin-right: 120px;">
                    {{ Form::open(['url' => 'gestion/usuarios/roles', 'class' => 'form-horizontal', 'id' => 'form_reg_rol']) }}
                        <div class="form-group">
                            {{ Form::label('nombretipo', 'Nombre', ['class' => 'control-label col-lg-4']) }}
                            <div class="col-lg-8">
                                {{ Form::text('nombretipo', '', ['required', 'class' => 'form-control', 'autofocus', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 360px;">
                            {{ Form::submit('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_rg_rol']) }}
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
{{ HTML::script('assets/own/js/gestion/usuarios/roles.js') }}
@stop