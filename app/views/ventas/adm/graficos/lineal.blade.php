@extends('layout')

@section('body')

<table style="margin-top: 20px;">
    <tr>
        <th>Año:</th>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td>{{ Form::selectYear('year', 2015, $ultimo_año, $seleccionar_año, ['id' => 'select_año', 'class' => 'form-control']) }}</td>    
    </tr>
</table>

<h3>Total de productos</h3>

<div id="graph_lineal" style="margin-bottom: 50px;">
    <div id="graph_lineal_act">
    	<div id="total_productos" style="height: 250px;"></div>
    </div>
</div>

<h3>Total de ingresos</h3>

<div id="graph_lineal" style="margin-bottom: 50px;">
    <div id="graph_lineal_act">
      <div id="total_ingresos" style="height: 250px;"></div>
    </div>
</div>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script type="text/javascript">
new Morris.Line({
  	// ID of the element in which to draw the chart.
  	element: 'total_productos',
  	// Chart data records -- each entry in this array corresponds to a point on
  	// the chart.
  	data: [
	    { year: '{{ $año }}-01', value: {{ $cantidad->enero }} },
	    { year: '{{ $año }}-02', value: {{ $cantidad->febrero }} },
	    { year: '{{ $año }}-03', value: {{ $cantidad->marzo }} },
	    { year: '{{ $año }}-04', value: {{ $cantidad->abril }} },
	    { year: '{{ $año }}-05', value: {{ $cantidad->mayo }} },
	    { year: '{{ $año }}-06', value: {{ $cantidad->junio }} },
	    { year: '{{ $año }}-07', value: {{ $cantidad->julio }} },
	    { year: '{{ $año }}-08', value: {{ $cantidad->agosto }} },
	    { year: '{{ $año }}-09', value: {{ $cantidad->septiembre }} },
	    { year: '{{ $año }}-10', value: {{ $cantidad->octubre }} },
	    { year: '{{ $año }}-11', value: {{ $cantidad->noviembre }} },
	    { year: '{{ $año }}-12', value: {{ $cantidad->diciembre }} },
  	],
  	// The name of the data record attribute that contains x-values.
  	xkey: 'year',
  	// A list of names of data record attributes that contain y-values.
  	ykeys: ['value'],
  	// Labels for the ykeys -- will be displayed when you hover over the
  	// chart.
  	labels: ['Cantidad']
});

new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'total_ingresos',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: [
      { year: '{{ $año }}-01', value: {{ $total->enero }} },
      { year: '{{ $año }}-02', value: {{ $total->febrero }} },
      { year: '{{ $año }}-03', value: {{ $total->marzo }} },
      { year: '{{ $año }}-04', value: {{ $total->abril }} },
      { year: '{{ $año }}-05', value: {{ $total->mayo }} },
      { year: '{{ $año }}-06', value: {{ $total->junio }} },
      { year: '{{ $año }}-07', value: {{ $total->julio }} },
      { year: '{{ $año }}-08', value: {{ $total->agosto }} },
      { year: '{{ $año }}-09', value: {{ $total->septiembre }} },
      { year: '{{ $año }}-10', value: {{ $total->octubre }} },
      { year: '{{ $año }}-11', value: {{ $total->noviembre }} },
      { year: '{{ $año }}-12', value: {{ $total->diciembre }} },
    ],
    // The name of the data record attribute that contains x-values.
    xkey: 'year',
    // A list of names of data record attributes that contain y-values.
    ykeys: ['value'],
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['Total']
});
</script>

@stop

@section('resources')
{{ HTML::script('assets/own/js/ventas/adm/graficos/lineal.js') }}
@stop