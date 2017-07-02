@extends('layout')

@section('resources')
<script>
$(function(){
    Metis.formGeneral();
});
</script>
@stop

@section('body')

<?php
Form::macro('date', function($name, $value){
    return '<input type="date" name="'.$name.'" value="'.$value.'" class="form-control" required step="1">';
});  
?>

<!--<h3>Movimientos <small>{{$anho}}</small></h3>-->

<br>

<table>  
    <tr>
        <td>&nbsp;&nbsp;</td>
        {{ Form::open(['url' => 'distribuidor/inventario/movimientos', 'class' => 'form-horizontal'])}}
        <td>{{ Form::text('fechas', $valFecha_ini.' - '.$valFecha_fin, ['class' => 'form-control', 'id' => 'reservation']) }}</td>
    <td>&nbsp;&nbsp;{{ Form::submit('Ver', ['class' => 'btn btn-default']) }}&nbsp;&nbsp;</td>

    {{ Form::close() }}
    <td>
      
      @foreach($trimestres as $key => $value)
  
        {{--La variable $k es dada desde el controlador el cual arroja valores entre 0 y 3--}}
        @if($key == $k)
          <label class="btn btn-success active">
            {{HTML::link('distribuidor/inventario/movimientos/'.$key, $value, ['style' => 'color:#FFF; text-decoration:none;'])}}
          </label>
        @else
          <label class="btn btn-success">
            {{HTML::link('distribuidor/inventario/movimientos/'.$key, $value, ['style' => 'color:#FFF; text-decoration:none;'])}}
          </label>
        @endif
          
      @endforeach

    </td>
    
  </tr>

</table>

<!--Begin Datatables-->
          <div id="tabla-inventarios">
            <div id="load-inventarios">
            <div class="row">
              <div class="col-lg-12">
                <div class="box">
                  <header>
                    <div class="icons">
                      <i class="fa fa-table"></i>
                    </div>
                    @if(count($movimientos) == 0)
                      <h5>Ningún Movimiento Registrado</h5>
                      
                    @else
                      <h5>Movimientos Realizados</h5>

                    @endif
                  </header>

                  <div id="mensaje">
                   
                  </div>
                  
                  <div id="collapse4" class="body">
                    <table id="data-table-movimientos" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                            <th></th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo</th>
                            <th>Total</th>
                            <th>Tipo Movimiento</th>
                        </tr>
                      </thead>
                      <tbody id="movimientos">
                          @foreach($movimientos as $m)
                          <tr>
                              <td>{{ $j++}}</td>
                              <td>{{ date('d/m/Y', strtotime($m->FechaMovimiento)) }}</td>
                              <td>{{ $m->NombreProducto }}</td>
                              <td style="text-align:center;">{{ $m->CantidadMovimiento }}</td>
                              <td style="text-align:center;">S/. {{ number_format($m->CostoMovimiento, 2) }}</td>
                              <td style="text-align:center;">S/. {{ number_format($m->TotalCosto, 2) }}</td>
                              <td>{{ $m->TipoMovimiento }}</td>                            
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
<span id="ur" name="{{ URL::to('gestion/inventario') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

@stop