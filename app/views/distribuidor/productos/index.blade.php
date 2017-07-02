@extends('layout')

@section('resources')
<script>
    function listaproductos(){
        var arr_codBarras = new Array();//tambien se puede declarar var arr_codBarras = [];
        var arr_NomProducto = new Array();

        @foreach($productosDist as $p)
            arr_codBarras.push('{{{$p->CodBarrasDist}}}');
            arr_NomProducto.push('{{{$p->NombreProductoDist}}}');
        @endforeach

        var arr_codBarras_gen = new Array();
            arr_codBarras_gen['CodBarrasDist'] = arr_codBarras;

        var arr_NomProducto_gen = new Array();
            arr_NomProducto_gen['NombreProductoDist'] = arr_NomProducto;

        var arreglo_asociativo = []; //cambio de [] puede ser new Array();
            arreglo_asociativo.push(arr_codBarras_gen);
            arreglo_asociativo.push(arr_NomProducto_gen);

        return arreglo_asociativo;
    }
</script>
{{ HTML::script('assets/own/js/distribuidor/productos/index.js') }}
{{ HTML::script('assets/lib/jquery-form/jquery.form.js') }}
<script>
  $(function() {
    Metis.MetisProgress();
  });
</script>
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
                                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#nuevo_producto" id="a_nuevo_producto">
                                    <i class="glyphicon glyphicon-file"></i>
                                    Nuevo Producto
                                </a>
                            </nav>
                        </div>
                    </header>
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
                                @foreach($productosDist as $prod)
                                <tr>
                                    <td>
                                        <a data-toggle="modal" href="#prod_det">
                                          @if(empty($prod->UrlFotoProductoDist))
                                            {{ HTML::image('assets/products_dist_img/product-default.png', $prod->NombreProductoDist, ['class' => 'icono_prod' , 'name' => $prod->DetallesProductoDist, 'width' => 20, 'height' => 20, 'data-id' => MD5($prod->idProductoDist), 'data-categoria' => $prod->idCategoriaProductoDist]) }}
                                          @else
                                            {{ HTML::image($prod->UrlFotoProductoDist, $prod->NombreProductoDist, ['class' => 'icono_prod' , 'name' => $prod->DetallesProductoDist, 'width' => 20, 'height' => 20, 'data-id' => MD5($prod->idProductoDist), 'data-categoria' => $prod->idCategoriaProductoDist]) }}
                                          @endif
                                        </a>
                                    </td>
                                    <td><label>{{{ $prod->CodBarrasDist }}}</label></td>
                                    <td>{{{ $prod->NombreProductoDist }}}</td>
                                    <td>{{{ $prod->NombreCategoriaProductoDist }}}</td>
                                    @if(strlen($prod->DetallesProductoDist) > 30)
                                    <td>{{ substr($prod->DetallesProductoDist, 0, 30) }}...</td>
                                    @else
                                    <td>{{ $prod->DetallesProductoDist }}</td>
                                    @endif
                                    <td>
                                        <span class="editar" data-id="{{{ MD5($prod->idProductoDist) }}}">
                                            <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#editar_producto">                   
                                                <li class="fa fa-pencil"></li>
                                            </a>
                                        </span>
                                        <span class="eliminar" data-id="{{{ MD5($prod->idProductoDist) }}}">
                                            <a class="btn btn-danger btn-sm" href="#eliminar_producto" data-toggle="modal">
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
        </div><!-- /.row -->
    </div>
</div>
<!--End Datatables-->

<!-- #helpModal Producto-->
<div id="nuevo_producto" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Registrar Productos</h4>
            </div>
            {{ Form::open(['url' => 'distribuidor/productos/registrar-producto', 'class' => 'form-horizontal', 'files'=>true, 'id' => 'frm_productos_dist']) }}
                <div class="modal-body">
                    <div id="div-1" class="body">
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Cod. Barras</label>
                            <div class="col-lg-8">
                                {{ Form::text('codbarras', '', ['required', 'class' => 'form-control cls-req', 'id' => 'txtcodbarras']) }}
                                <label class="error_vacio" style="display:none; color: red; font-size: 12px; font-family: cursive"></label>
                                <label id="error_codbarras" style="display:none; color: red; font-size: 12px; font-family: cursive"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Producto</label>
                            <div class="col-lg-8">
                                {{ Form::text('producto', '', ['required', 'class' => 'form-control cls-req', 'id' => 'txtnomProducto']) }}
                                <label class="error_vacio" style="display:none; color: red; font-size: 12px; font-family: cursive"></label>
                                <label id="error_nomProducto" style="display:none; color: red; font-size: 12px; font-family: cursive"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Detalles</label>
                            <div class="col-lg-8">
                                {{ Form::textarea('detalles', '', ['class' => 'form-control']) }}
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-3">Categoría</label>
                            <div class="col-lg-8">
                                {{ Form::select('categoria', $listacategoria, '', ['class' => 'form-control chzn-select cls-req', 'data-placeholder' => 'Elija un tipo de categoria...', 'tabindex' => '2']) }}
                                <label class="error_vacio" style="display:none; color: red; font-size: 12px; font-family: cursive"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions no-margin-bottom">
                        {{ Form::submit('Registrar', ['class' => 'btn btn-primary', 'id' => 'btn_RegProdDist']) }}
                    </div>                
                </div>
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->

<!-- #helpModal ImgProducto-->
<div id="UploadImgProducto" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Subir Imagen</h4>
            </div>
            {{ Form::open(['url' => 'distribuidor/productos/upload-imgproducto', 'class' => 'form-horizontal', 'files'=>true, 'id' => 'frm_productos_dist_upload']) }}
                <div class="modal-body">
                    <div id="div-1" class="body">
                        <center>
                            <div class="bg-red lter" id="msg_alert" style="margin: 0 0 10px 0; padding:10px 10px 10px 10px;"></div>
                        </center>
                        <div class="form-group">
                        <label class="control-label col-lg-4">Image Upload</label>
                        <div class="col-lg-8">
                          <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                            <div>
                              <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span> <span class="fileinput-exists">Change</span> 
                                <input type="file" name="img_file">
                              </span> 
                              <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a> 
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group" id="div_progress">
                            <label class="control-label col-lg-4">Subiendo...</label>
                            <div class="col-lg-6">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                       <span class="sr-only">0% Complete (success)</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions no-margin-bottom">
                        {{ Form::hidden('id_prod_dist_ult', '', ['id' => 'id_prod_dist_ult']) }}
                        {{ Form::submit('Subir', ['class' => 'btn btn-primary', 'id' => 'btn_UploadProdDist']) }}
                        
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
                        {{ Form::open(['url' => 'distribuidor/productos/upload-imgproducto', 'files' => true, 'class' => 'form-vertical', 'id' => 'form_cambiar_img_prod']) }}
                            <div class="bg-red lter" id="msg_alert2" style="margin: 0 0 10px 0; padding:10px 10px 10px 10px;"></div>
                            <div class="form-group">
                                <div id="imagen_producto" align="center"></div>
                                <br>
                                <span id="url_img_prod" url="{{{ URL::to('assets/products_dist_img/') }}}"></span>
                                    {{ Form::file('img_file', ['required', 'id' => 'camb_img_file', 'data-filename-placement' => 'inside', 'title' => 'Escoger nueva imagen']) }}
                            </div>
                            <div id="inp"></div>
                            <div class="form-actions no-margin-bottom">
                                {{ Form::submit('Cambiar Imagen', ['class' => 'btn btn-primary', 'id' => 'btn_cambiar_img_prod']) }}
                            </div>
                            <br>
                            <div class="form-group" id="div_progress2">
                                <label class="control-label col-lg-4">Subiendo...</label>
                                <div class="col-lg-6">
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                           <span class="sr-only">0% Complete (success)</span> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        {{ Form::close() }}
                    </center>
                </div>
            </div>
            <div class="modal-footer">
                <center><b>Detalle</b></center>
                <div id="detalle_producto" align="center"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><!-- /#helpModal -->


<span id="ur" name="{{ URL::to('distribuidor/productos') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>



@stop