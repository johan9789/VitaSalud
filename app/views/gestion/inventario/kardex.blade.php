@extends('layout')

@section('resources')
{{ HTML::script('assets/own/js/gestion/inventario/kardex.js') }}
<script>
$(function(){
    Metis.formGeneral();
});
</script>
<style type="text/css">
  #tabladatoskardex td{
    padding: 3px 7px;
  }
</style>
@stop

@section('body')

<br>

<table id="tabladatoskardex">  
    <tr>
      <td colspan="2">
        {{ Form::open(['url' => 'gestion/inventario/kardex', 'class' => 'form-horizontal'])}}
      <select  data-placeholder="Seleccione Producto..." class="form-control chzn-select slc_producto" tabindex="5" name="sel_prod_inv[]" id="sel_prod_inv1" data-id="1">
        <option value="0"></option>
        @foreach($matriz as $key => $value)  
          <optgroup label="{{$key}}">
          @foreach($value as $key1 => $value1)
            @foreach($value1 as $key2 => $value2)
              <option value="{{$key2}}">{{$value2}}</option>
            @endforeach
          @endforeach
          </optgroup>
        @endforeach
      </select>
    </td>

    <td>{{ Form::text('fechas', $valFecha_ini.' - '.$valFecha_fin, ['class' => 'form-control', 'id' => 'reservation']) }}</td>
    <td>{{ Form::submit('Buscar') }}</td>
    
  </tr>
  <tr>
    <td>
      Cod. Barras: <input type="text" class="form-control codigo" required="" id="codigo" data-id="1">
    </td>
    <td>
      Cod. Producto: <input type="text" class="form-control codigobarras" required="" id="codbarras" data-id="1">
    </td>


  
    {{ Form::close() }}
    <td colspan="2">
      Kardex por trimestre:<br>
      @foreach($trimestres as $key => $value)
  
        {{--La variable $k es dada desde el controlador el cual arroja valores entre 0 y 3--}}
        @if($key == $k)
          <label class="btn btn-success active">
            {{HTML::link('gestion/inventario/kardex/'.$key, $value, ['style' => 'color:#FFF; text-decoration:none;'])}}
          </label>
        @else
          <label class="btn btn-success">
            {{HTML::link('gestion/inventario/kardex/'.$key, $value, ['style' => 'color:#FFF; text-decoration:none;'])}}
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
                    @if(count($kardex) == 0)
                      <h5>Sin datos Registrados</h5>
                      
                    @else
                      <h5>Kardex</h5>

                    @endif
                  </header>

                  <div id="mensaje">
                   
                  </div>
                  
                  <div id="collapse4" class="body">
                    <table class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                          <th rowspan="2">Nro</th>
                          <th rowspan="2">Fecha</th>
                          <th rowspan="2">Descripción</th>
                          <th colspan="3">Entradas</th>
                          <th colspan="3">Salidas</th>
                          <th colspan="3">Existencias</th>
                        </tr>
                        <tr>
                          <th>Cantidad</th>
                          <th>V.U.</th>
                          <th>V.T.</th>
                          <th>Cantidad</th>
                          <th>V.U.</th>
                          <th>V.T.</th>
                          <th>Cantidad</th>
                          <th>V.U.</th>
                          <th>V.T.</th>
                        </tr>
                      </thead>
                      <tbody id="movimientos">
                          @foreach($kardex as $m)
                          <tr>
                              <td>{{ $j++}}</td>
                              <td>{{ $m->FechaMovimiento }}</td>
                              <td>{{ $m->NombreProducto }}</td>
                              <td style="text-align:center;">{{ $m->CantidadMovimiento }}</td>
                              <td style="text-align:center;">S/. {{ number_format($m->CostoMovimiento, 2) }}</td>
                              <td style="text-align:center;">S/. {{ number_format($m->TotalCosto, 2) }}</td>
                              <td>{{ $m->TipoMovimiento }}</td>
                              <td>{{ $m->RegistradoPor }}</td>
                              <td>{{ $j++}}</td>
                              <td>{{ $j++}}</td>
                              <td>{{ $j++}}</td>
                              <td>{{ $j++}}</td>
                                                            
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
