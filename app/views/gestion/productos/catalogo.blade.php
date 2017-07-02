@extends('layout')

@section('resources')
  {{ HTML::script('assets/own/js/gestion/productos/catalogo.js') }}
@stop

@section('body')

<div class="row">              
    <div class="col-lg-12">                
        <br>
        <div class="btn-group" data-toggle="buttons" id="dark-toggle">
            <label class="btn btn-success active">
                <input class="rad-list-categ" type="radio" name="list-categoria" value='0'>Todos
            </label>
            @foreach($listacategoria as $lc)
            <label class="btn btn-success">
                <input class="rad-list-categ" type="radio" name="list-categoria" value='{{ $lc->idCategoriaProducto}}'>
                {{$lc->NombreCategoriaProducto}}
            </label>
            @endforeach
        </div>
        <ul class="pricing-table" id="light">
          	@foreach($productos as $p)
            <li class="col-lg-3">
                <h3>{{ $p->NombreProducto }}</h3>
                <div class="price-body">
                    <div class="price">
                        @if(empty($p->UrlFotoProducto))
                        {{ HTML::image('assets/products_img/product-default.png', 'producto', ['class' => 'icono_prod' , 'name' => $p->DetallesProducto, 'width' => 120, 'height' => 120, 'style' => 'padding: 10px; border-radius: 100%']) }}
                        @else
                        {{ HTML::image('assets/products_img/'.$p->UrlFotoProducto, 'producto', ['class' => 'icono_prod' , 'name' => $p->DetallesProducto, 'width' => 120, 'height' => 120, 'style' => 'padding: 10px; border-radius: 100%']) }}
                        @endif
                    </div>
                </div>
                <div class="features">
                    <ul>
                        <li><strong>S/. {{ number_format($p->PrecPublico, 2) }}</strong></li>
                        @if(strlen($p->DetallesProducto) > 30)
                        <li>{{ substr($p->DetallesProducto, 0, 30) }}...</li>
                        @else
                        <li>{{ $p->DetallesProducto }}</li>
                        @endif
                    </ul>
                </div>
                <div class="footer">
                    {{ HTML::link('#verFicha', 'Ver Ficha', ['class' => 'btn btn-info btn-rect', 'id' => 'a-verFicha', 'data-toggle' => 'modal']) }}
                </div>
            </li>
          	@endforeach
            <div class="clearfix"></div>
        </ul>
    </div>
</div>

<!-- #detalle_pedido:modal -->
<div id="verFicha" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">NombreProducto</h4>
            </div>
            <center><div class="loader"></div></center>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <div class="form-group">
                        <div id="imagen_producto" align="center"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text1" class="control-label col-lg-3">Detalle: </label>
                    <div class="col-lg-8"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="detalle_producto" align="center"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<span id="ur" name="{{ URL::to('catalogo') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

@stop