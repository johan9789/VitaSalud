@extends('layout')

@section('resources')
{{ HTML::script('assets/own/js/gestion/clientes/index.js') }}
@stop

@section('body')

<br>
<div class="btn-group" data-toggle="buttons" id="dark-toggle">
    <label class="btn btn-success active">
        <input class="rad-list-categ" type="radio" name="list-cliente" value='0'>Persona
    </label>
    <label class="btn btn-success">
        <input class="rad-list-categ" type="radio" name="list-cliente" value='1'>Empresa
    </label>
</div>

<?php $i = 1; ?>
<!--Begin Datatables-->
<div id="tabla-clientesN">
    <div id="load-clientesN">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons">
                            <i class="fa fa-table"></i>
                        </div>
                        <h5>Persona</h5>
                    </header>
                    <div id="collapse4" class="body">
                        <table id="dataTableClientesN" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>DNI</th>
                                    <th>Teléfono</th>
                                    <th>Celular</th>
                                    <th>E-mail</th>
                                    <th>Dirección</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="inventario">
                                @foreach($clientesN as $cn)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $cn->persona['Nombres'] }}</td>
                                        <td>{{ $cn->persona['Apellidos'] }}</td>
                                        <td>{{ $cn->persona['DNI'] }}</td>
                                        <td>{{ $cn->persona['Telefono'] }}</td>
                                        <td>{{ $cn->persona['Celular'] }}</td>
                                        <td>{{ $cn->persona['email'] }}</td>
                                        <td>{{ $cn->persona['Direccion'] }}</td>
                                        <td>
                                            <span class="editar" data-id="N">
                                              <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#editar_clienteN" data-id="{{ $cn->persona['idPersona'] }}">
                                                  <i class="fa fa-pencil"></i>
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
        </div><!-- /.row -->
    </div>
</div>
<!--End Datatables-->

<?php $i = 1; ?>
<!--Begin Datatables-->
<div id="tabla-clientesJ" style="display:none;">
    <div id="load-clientesJ">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons">
                            <i class="fa fa-table"></i>
                        </div>
                        <h5>Empresa</h5>
                    </header>
                    <div id="collapse4" class="body">
                        <table id="dataTableClientesJ" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empresa</th>
                                    <th>RUC</th>
                                    <th>Teléfono</th>
                                    <th>E-mail</th>
                                    <th>Dirección</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="inventario">
                                @foreach($clientesJ as $cj)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $cj->empresa['NombreEmpresa'] }}</td>
                                        <td>{{ $cj->empresa['RUC'] }}</td>
                                        <td>{{ $cj->empresa['TelefonoEmpresa'] }}</td>
                                        <td>{{ $cj->empresa['EmailEmpresa'] }}</td>
                                        <td>{{ $cj->empresa['DireccionEmpresa'] }}</td>
                                        <td>
                                            <span class="editar" data-id="J">
                                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#editar_clienteJ" data-id="{{$cj->empresa['idEmpresa']}}">
                                                    <i class="fa fa-pencil"></i>
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
        </div><!-- /.row -->
    </div>
</div>
<!--End Datatables-->

<!-- #editar_clienteN:modal -->
<div id="editar_clienteN" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Cliente</h4>
            </div>
            <div id="loaderN"></div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'gestion/clientes/actualizar-clientes', 'class' => 'form-horizontal', 'id' => 'form_editar_cliente']) }}
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Nombre</label>
                        <div class="col-lg-8">{{ Form::text('nombre', '', ['required', 'class' => 'form-control', 'autofocus', 'id' => 'ed_clN_nombre']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Apellidos</label>
                        <div class="col-lg-8">{{ Form::text('apellidos', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_aps']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">D.N.I.</label>
                        <div class="col-lg-8">{{ Form::number('dni', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_dni']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Teléfono</label>
                        <div class="col-lg-8">{{ Form::number('telefono', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_telefono']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Celular</label>
                        <div class="col-lg-8">{{ Form::number('celular', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_celular']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">E-mail</label>
                        <div class="col-lg-8">{{ Form::email('email', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_email']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Dirección</label>
                        <div class="col-lg-8">{{ Form::text('direccion', '', ['required', 'class' => 'form-control', 'id' => 'ed_clN_direccion']) }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-actions no-margin-bottom">
                    {{ Form::hidden('idPersona', '', ['id' => 'ed_clN_idPersona']) }}
                    {{ Form::submit('Modificar', ['class' => 'btn btn-primary', 'id' => 'modificar']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#editar_clienteN:modal -->

<!-- #editar_clienteJ:modal -->
<div id="editar_clienteJ" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Cliente</h4>
            </div>
            <div id="loaderJ"></div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'gestion/clientes/actualizar-empresas', 'class' => 'form-horizontal', 'id' => 'form_editar_empresa']) }}
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Empresa</label>
                        <div class="col-lg-8">{{ Form::text('empresa', '', ['required', 'class' => 'form-control', 'autofocus', 'id' => 'ed_clJ_empresa']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Ruc</label>
                        <div class="col-lg-8">{{ Form::text('ruc', '', ['required', 'class' => 'form-control', 'id' => 'ed_clJ_ruc']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Teléfono</label>
                        <div class="col-lg-8">{{ Form::number('telefono', '', ['required', 'class' => 'form-control', 'id' => 'ed_clJ_telefono']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">E-mail</label>
                        <div class="col-lg-8">{{ Form::email('email', '', ['required', 'class' => 'form-control', 'id' => 'ed_clJ_email']) }}</div>
                    </div>
                    <div class="form-group">
                        <label for="text1" class="control-label col-lg-3">Dirección</label>
                        <div class="col-lg-8">{{ Form::text('direccion', '', ['required', 'class' => 'form-control', 'id' => 'ed_clJ_direccion']) }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-actions no-margin-bottom">
                    {{ Form::hidden('idEmpresa', '', ['id' => 'ed_clJ_idEmpresa']) }}
                    {{ Form::submit('Modificar', ['class' => 'btn btn-primary', 'id' => 'modificar']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#editar_clienteJ:modal -->

<span id="ur" name="{{ URL::to('gestion/clientes') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

@stop
