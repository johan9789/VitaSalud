@extends('layout')

@section('body')

<table style="margin-top: 20px;">
    <tr>
        <th>Rango:</th>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td>{{ Form::text('dates', '', ['class' => 'form-control', 'id' => 'reservation', 'placeholder' => $fechas]) }}</td>
        <td>&nbsp;</td>
        <td>{{ Form::button('Ver', ['class' => 'btn btn-default', 'id' => 'btn_int_fechas']) }}</td>
    </tr>
</table>

<div>
    <h3>Total de productos</h3>
</div>

<div id="graph_fechas">
    <div id="graph_fechas_act">
        <div class="col-lg-6">
            <div class="content-panel">
          		  <div class="panel-body">
          		      @if(count($venta_actual) != 0)
          			    <div id="hero-donut" class="graph"></div>
        		        @else
        	 	        No hay ventas en el día realizadas aún.
          		      @endif
              	</div>	
          	</div>
        </div>
        <div class="col-lg-6">
            <div class="content-panel">          
                <div class="panel-body">
                    <div id="hero-bar" class="graph"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div style="margin-top: 400px;">
    <h3>Total de ingresos</h3>
</div>

<div id="graph_fechas">
    <div id="graph_fechas_act">
        <div class="col-lg-6">
            <div class="content-panel">
                  <div class="panel-body">
                      @if(count($venta_actual) != 0)
                        <div id="hero-donut2" class="graph"></div>
                        @else
                        No hay ventas en el día realizadas aún.
                      @endif
                </div>  
            </div>
        </div>
        <div class="col-lg-6">
            <div class="content-panel">          
                <div class="panel-body">
                    <div id="hero-bar2" class="graph"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script type="text/javascript">
var Script = function(){

    $(function(){

        Morris.Donut({
            element: 'hero-donut',
            data: [
                <?php foreach($venta_actual as $ven): ?>
                {label: '{{ $ven->NombreProductoDist }}', value: {{ $ven->cantidad_final }} },
                <?php endforeach; ?>                
            ],
            colors: ['#3498db', '#2980b9', '#34495e'],
            formatter: function(y){
                return y
            }
        });

        Morris.Bar({
            element: 'hero-bar',
            data: [
                <?php foreach($venta_actual as $ven): ?>
                {device: '{{ $ven->NombreProductoDist }}', geekbench: {{ $ven->cantidad_final }} },
                <?php endforeach; ?>
            ],
            xkey: 'device',
            ykeys: ['geekbench'],
            labels: ['Cantidad'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            barColors: ['#ac92ec']
        });      

        Morris.Donut({
            element: 'hero-donut2',
            data: [
                <?php foreach($venta_actual_ingresos as $ven): ?>
                {label: '{{ $ven->NombreProductoDist }}', value: {{ $ven->ingreso_total }} },
                <?php endforeach; ?>                
            ],
            colors: ['#3498db', '#2980b9', '#34495e'],
            formatter: function(y){
                return 'S./ ' + y
            }
        });

        Morris.Bar({
            element: 'hero-bar2',
            data: [
                <?php foreach($venta_actual_ingresos as $ven): ?>
                {device: '{{ $ven->NombreProductoDist }}', geekbench: {{ $ven->ingreso_total }} },
                <?php endforeach; ?>
            ],
            xkey: 'device',
            ykeys: ['geekbench'],
            labels: ['Ingresos'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            barColors: ['#ac92ec']
        });  

    });

}();
</script>
@stop

@section('resources')
{{ HTML::script('assets/own/js/ventas/adm/graficos/index.js') }}
@stop