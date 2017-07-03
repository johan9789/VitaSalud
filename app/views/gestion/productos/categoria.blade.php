@extends('layout')

@section('resources')
{{ HTML::script('assets/own/js/gestion/productos/categoria.js') }}
@stop

@section('body')

<!--Begin Datatables-->
<div id="tabla-categoria">
    <div id="load-categoria">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Lista de Categorías</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nueva_categoria">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nueva Categoria
                                </a>
                            </nav>
                        </div>
                    </header>
                    <div id="mensaje">
                        @if(Session::has('mensaje'))
                        {{-- Session::get('mensaje') --}}
                        @endif
                    </div>
                    <div id="collapse4" class="body">
                        <table id="data_table_categoria" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="productos">
                                @foreach($categorias as $i => $categoria)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $categoria->NombreCategoriaProducto }}</td>
                                    <td>{{ $categoria->DescripcionCategoriaProducto }}</td>
                                    <td>
                                        <span onclick="editarCategoria('{{ $categoria->idCategoriaProducto }}')">
                                            <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#editar_categoría">                   
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                        <span onclick="eliminarCategoria('{{ $categoria->idCategoriaProducto }}')">
                                            <a class="btn btn-danger btn-sm" href="#eliminar_producto" data-toggle="modal">
                                                <i class="fa fa-trash"></i>
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

<span id="ur" name="{{ URL::to('categoria') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

<!-- #helpModal Categoria-->
<div id="nueva_categoria" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar Categoria</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'gestion/productos/registrarcat', 'class' => 'form-horizontal']) }}    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Categoria</label>
                            <div class="col-lg-8">
                                {{ Form::text('categoria', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Descripción</label>
                            <div class="col-lg-8">
                                {{ Form::textarea('descripcion', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        {{ Form::hidden('ubicacion', 'categoria') }}
                        <div class="form-actions no-margin-bottom">
                            <center>{{ Form::submit('Registrar', ['class' => 'btn btn-primary']) }}</center>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->

<!-- #helpModal Editar Producto-->
<div id="editar_categoría" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Categoría</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'gestion/productos/modificarcategoria', 'class' => 'form-horizontal', 'id' =>'form_editar_cat']) }}    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Categoría</label>
                            <div class="col-lg-8">
                                {{ Form::text('categoria', '', ['required', 'class' => 'form-control', 'id' =>'txtcategoria']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Detalles</label>
                            <div class="col-lg-8">
                                {{ Form::textarea('descripcion', '', ['required', 'class' => 'form-control', 'id' =>'txtadescripcion']) }}
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom">
                            <center>{{ Form::submit('Modificar', ['class' => 'btn btn-primary', 'id' => 'modificar']) }}</center>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!--  /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->

@stop