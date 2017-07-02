@extends('layout')

@section('resources')
<script>
	function listaproductos(){
		var arreglo = new Array();
		@foreach($productos as $p)
			arreglo.push({{{$p->CodBarras}}});
		@endforeach
		return arreglo;
	}
</script>
{{ HTML::script('assets/own/js/gestion/productos/index.js') }}
@stop

@section('body')

<!--Begin Datatables-->
<div id="tabla-productos">
    <div id="load-productos">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Lista de Productos registrados</h5>
                        <div class="toolbar">
                            <nav style="padding: 5px;">
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nueva_categoria">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nueva Categoria
                                </a>    
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nuevo_producto">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nuevo Producto
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
                        <table id="data_table_productos" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Cod. Barras</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Detalle</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="productos">
                                @foreach($productos as $prod)
                                <tr>
                                    <td>
                                        <a data-toggle="modal" href="#prod_det">
                                          @if(empty($prod->UrlFotoProducto))
                                            {{ HTML::image('assets/products_img/product-default.png', $prod->NombreProducto, ['class' => 'icono_prod' , 'name' => $prod->DetallesProducto, 'width' => 20, 'height' => 20, 'data-id' => $prod->idProducto]) }}
                                          @else
                                            {{ HTML::image('assets/products_img/'.$prod->UrlFotoProducto, $prod->NombreProducto, ['class' => 'icono_prod' , 'name' => $prod->DetallesProducto, 'width' => 20, 'height' => 20, 'data-id' => $prod->idProducto]) }}
                                          @endif
                                        </a>
                                    </td>
                                    <td><label>{{{ $prod->CodBarras }}}</label></td>
                                    <td>{{{ $prod->NombreProducto }}}</td>
                                    <td>{{{ $prod->NombreCategoriaProducto }}}</td>
                                    @if(strlen($p->DetallesProducto) > 30)
                                    <td>{{ substr($prod->DetallesProducto, 0, 30) }}...</td>
                                    @else
                                    <td>{{ $prod->DetallesProducto }}</td>
                                    @endif
                                    <td>
                                        <span class="editar" data-id="{{{ $prod->idProducto }}}">
                                            <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#editar_producto">                   
                                                <li class="fa fa-pencil"></li>
                                            </a>
                                        </span>
                                        <span class="eliminar" data-id="{{{ $prod->idProducto }}}">
                                            <a class="btn btn-danger btn-sm" href="#eliminar_producto" data-toggle="modal">
                                                <li class="fa fa-trash"></li>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <?php echo "Jhontan"; ?>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div>
</div>
<!--End Datatables-->

<span id="ur" name="{{ URL::to('gestion/productos') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

<!-- #helpModal Producto-->
<div id="nuevo_producto" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar Productos</h4>
            </div>
            {{ Form::open(['url' => 'gestion/productos/registrarprod', 'class' => 'form-horizontal', 'files'=>true]) }}
                <div class="modal-body">
                    <div id="div-1" class="body">
                    	<div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Cod. Barras</label>
                            <div class="col-lg-8">
                                {{ Form::text('codbarras', '', ['required', 'class' => 'form-control', 'id' => 'txtcodbarras']) }}
                                <label id="error_codbarras" style="display:none; color: red;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Producto</label>
                            <div class="col-lg-8">
                                {{ Form::text('producto', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Detalles</label>
                            <div class="col-lg-8">
                                {{ Form::textarea('detalles', '', ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Foto</label>
                            <div class="col-lg-8">
                                {{ Form::file('img_file', ['required', 'class' => 'form-control', 'id' => 'img_file', 'title' => 'Imagen del nuevo producto', 'data-filename-placement' => 'inside']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Categoría</label>
                            <div class="col-lg-8">
                                {{ Form::select('categoria', $categoriaproductos, '', ['class' => 'form-control chzn-select', 'data-placeholder' => 'Elija un tipo de categoria...', 'tabindex' => '2']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions no-margin-bottom">
                        {{ Form::submit('Registrar', ['class' => 'btn btn-primary']) }}
                    </div>                
                </div>
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->

<!-- #helpModal Producto Detalle-->
<div id="prod_det" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <center>
                        {{ Form::open(['url' => 'gestion/productos/cambimgprod', 'files' => true, 'class' => 'form-vertical', 'id' => 'form_cambiar_img_prod']) }}
                            <div class="form-group">
                                <div id="imagen_producto" align="center"></div>
                                <br>
                                <span id="url_img_prod" url="{{{ URL::to('assets/products_img/') }}}"></span>
                                    {{ Form::file('camb_img_file', ['required', 'id' => 'camb_img_file', 'data-filename-placement' => 'inside', 'title' => 'Escoger nueva imagen']) }}
                            </div>
                            <div id="inp"></div>
                            <div class="form-actions no-margin-bottom">
                                {{ Form::button('Cambiar Imagen', ['class' => 'btn btn-primary', 'id' => 'btn_cambiar_img_prod']) }}
                            </div>
                        {{ Form::close() }}
                    </center>
                </div>
                <div class="form-group">
                    <label for="text1" class="control-label col-lg-4">Detalle: </label>
                    <div class="col-lg-8"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="detalle_producto" align="center"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->

<!-- #helpModal Editar Producto-->
<div id="editar_producto" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar Producto</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'gestion/productos/modificarprod', 'class' => 'form-horizontal', 'id' =>'form_editar']) }}
                    	<div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Cod. Barras</label>
                            <div class="col-lg-8">
                                {{ Form::text('codbarras', '', ['required', 'class' => 'form-control', 'id' =>'codbarras', 'disabled' => 'disabled']) }}
                            </div>
                        </div>    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Producto</label>
                            <div class="col-lg-8">
                                {{ Form::text('producto', '', ['required', 'class' => 'form-control', 'id' =>'producto']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Detalles</label>
                            <div class="col-lg-8">
                                {{ Form::textarea('detalles', '', ['required', 'class' => 'form-control', 'id' =>'detalles']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Categoría</label>
                            <div class="col-lg-8">
                                {{ Form::select('categoria', $categoriaproductos, '', ['class' => 'form-control chzn-select', 'data-placeholder' => 'Elija un tipo de usuario...', 'tabindex' => '2', 'id' =>'categoria']) }}
                            </div>
                        </div>
                        
                    
                </div>
            </div>
            <div class="modal-footer">
            	<div class="form-actions no-margin-bottom">
                    {{ Form::submit('Modificar', ['class' => 'btn btn-primary', 'id' => 'modificar']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div><!--  /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->


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
                        {{ Form::hidden('ubicacion', 'productos') }}
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

@stop