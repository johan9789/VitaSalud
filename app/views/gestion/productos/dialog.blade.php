<!DOCTYPE html>
<html class="no-js">
<head>
<meta charset="UTF-8">
<title>Sistema de Gestion</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href="{{ URL::to('').'/' }}" target="">
{{ HTML::style('assets/lib/bootstrap/css/bootstrap.min.css') }}
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="http://arthurgouveia.com/prettyCheckable/js/prettyCheckable/dist/prettyCheckable.css">
{{ HTML::style('assets/css/main.min.css') }}
{{ HTML::style('assets/lib/fullcalendar/fullcalendar.css') }}
{{ HTML::style('assets/css/style-switcher.css') }}
{{ HTML::style('assets/css/less/theme.less', ['rel' => 'stylesheet/less']) }}
{{ HTML::script('assets/lib/less/less-1.7.3.min.js') }}
{{ HTML::script('assets/lib/modernizr/modernizr.min.js') }}    
{{ HTML::style('assets/lib/datatables/3/dataTables.bootstrap.css') }}
{{ HTML::style('assets/own/css/pagination.css') }}
{{ HTML::style('assets/own/css/contenedor.css') }}
{{ HTML::style('assets/lib/jquery.uniform/themes/default/css/uniform.default.css') }}
{{ HTML::style('assets/lib/inputlimiter/jquery.inputlimiter.css') }}
{{ HTML::style('assets/lib/chosen/chosen.min.css') }}
{{ HTML::style('assets/lib/colorpicker/css/colorpicker.css') }}
{{ HTML::style('assets/css/colorpicker_hack.css') }}
{{ HTML::style('assets/lib/tagsinput/jquery.tagsinput.css') }}
{{ HTML::style('assets/lib/daterangepicker/daterangepicker-bs3.css') }}
{{ HTML::style('assets/lib/datepicker/css/datepicker.css') }}
{{ HTML::style('assets/lib/timepicker/css/bootstrap-timepicker.min.css') }}
{{ HTML::style('assets/lib/switch/css/bootstrap3/bootstrap-switch.min.css') }}
{{ HTML::style('assets/lib/jasny-bootstrap/css/jasny-bootstrap.min.css') }}
{{ HTML::style('assets/css/assets/css/less/theme.less') }}
</head>
<body> 

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
                                    <th>Prec. Dist.</th>
                                    <th>Prec. Pub.</th>
                                    <th>Categoría</th>
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
                                    <td><label>{{ $prod->CodBarras }}</label></td>
                                    <td>{{ $prod->NombreProducto }}</td>
                                    <td>S/. {{ number_format($prod->PrecioDistribuidor, 2) }}</td>
                                    <td>S/. {{ number_format($prod->PrecioPublico, 2) }}</td>
                                    <td>{{ $prod->NombreCategoriaProducto }}</td>
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
                        <?php
                        //Para crear campo numerico u otro
                        Form::macro('numberXP', function($name){
                            return '<input type="number" name="'.$name.'" class="form-control" required min="0.01" max="999999.99" step="0.01">';
                        });
                        ?>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Distribuidor</label>
                            <div class="col-lg-8">
                                {{ Form::numberXP('precdistribuidor', '', ['required', 'class' => 'form-control']) }}
                             </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Publico</label>
                            <div class="col-lg-8"> 
                                {{ Form::numberXP('precpublico', '', ['required', 'class' => 'form-control']) }}
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
                                <label for="text1" class="control-label col-lg-3">Nueva Imagen: </label>
                                <span id="url_img_prod" url="{{ URL::to('assets/products_img/') }}"></span>
                                <div class="col-lg-8"> 
                                    {{ Form::file('camb_img_file', ['required', 'class' => 'form-control', 'id' => 'camb_img_file']) }}
                                </div>
                            </div>  
                            <br>
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
                        <?php
                        // Para crear campo numerico u otro
                        Form::macro('numberXD', function($name, $id){
                            return '<input type="number" name="'.$name.'" class="form-control" required min="1" max="10000" id="'.$id.'">';
                        });
                        ?>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Distribuidor</label>
                            <div class="col-lg-8">
                                {{ Form::numberXD('precdistribuidor', 'precdistribuidor') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Prec. Publico</label>
                            <div class="col-lg-8"> 
                                {{ Form::numberXD('precpublico', 'precpublico') }}
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


{{ Form::hidden('', Request::url(), ['id' => 'url_actual']) }}
{{ Form::hidden('', URL::to(''), ['id' => 'generar_url']) }}

{{ HTML::script('assets/lib/jquery/jquery.min.js') }}
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
{{ HTML::script('assets/own/js/layout.js') }}
{{ HTML::style('assets/own/css/apprise.css') }}
{{ HTML::style('assets/own/css/jquery-ui-1.8.17.custom.css') }}
{{ HTML::script('assets/own/js/resources/jquery-ui-1.8.9.custom.min.js') }}
{{ HTML::script('assets/own/js/resources/apprise-1.5.full.js') }}
{{ HTML::script('assets/lib/datatables/jquery.dataTables.js') }}
{{ HTML::script('assets/lib/datatables/3/dataTables.bootstrap.js') }}
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
{{ HTML::script('assets/lib/bootstrap/js/bootstrap.min.js') }}
{{ HTML::script('assets/own/js/resources/bootstrap.file-input.js') }}
<script type="text/javascript" src="http://arthurgouveia.com/prettyCheckable/js/prettyCheckable/dev/prettyCheckable.js"></script>

{{ HTML::script('assets/own/js/gestion/productos/index.js') }}

{{ HTML::script('assets/lib/screenfull/screenfull.js') }}
{{ HTML::script('assets/lib/moment/moment.min.js') }}
{{ HTML::script('assets/lib/fullcalendar/fullcalendar.min.js') }}
{{ HTML::script('assets/lib/jquery.tablesorter/jquery.tablesorter.min.js') }}
{{ HTML::script('assets/lib/jquery.sparkline/jquery.sparkline.min.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.selection.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.resize.js') }}
{{ HTML::script('assets/js/core.js') }}
{{ HTML::script('assets/js/app.min.js') }}

{{ HTML::script('assets/lib/jquery.uniform/jquery.uniform.min.js') }}
{{ HTML::script('assets/lib/inputlimiter/jquery.inputlimiter.js') }}
{{ HTML::script('assets/lib/chosen/chosen.jquery.min.js') }}
{{ HTML::script('assets/lib/colorpicker/js/bootstrap-colorpicker.js') }}
{{ HTML::script('assets/lib/tagsinput/jquery.tagsinput.js') }}
{{ HTML::script('assets/lib/validVal/js/jquery.validVal.min.js') }}
{{ HTML::script('assets/lib/daterangepicker/daterangepicker.js') }}
{{ HTML::script('assets/lib/datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('assets/lib/timepicker/js/bootstrap-timepicker.min.js') }}
{{ HTML::script('assets/lib/switch/js/bootstrap-switch.min.js') }}
{{ HTML::script('assets/lib/autosize/jquery.autosize.min.js') }}
{{ HTML::script('assets/lib/jasny-bootstrap/js/jasny-bootstrap.min.js') }}
<script type="text/javascript">
// $("div#contenedor_central").height(($(document).height()) - 178);
$('div#contenedor_central').css('min-height', ($(document).height()) - 178);
$(function(){
    Metis.dashboard();
});
</script>
{{ HTML::script('assets/js/style-switcher.js') }}

@if(Session::has('mensaje'))
<script type="text/javascript">
apprise('{{ Session::get('mensaje') }}', {'animate':true});
</script>
@endif

</body>
</html>