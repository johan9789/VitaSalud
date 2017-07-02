@extends('layout')

@section('body')

<h3>Total de productos</h3>

<div id="graph_lineal" style="margin-top: 20px; margin-bottom: 50px;">
    <div id="graph_lineal_act">
    	<div id="total_productos" style="height: 250px;"></div>
    </div>
</div>

<h3>Total de ingresos</h3>

<div id="graph_lineal" style="margin-top: 20px; margin-bottom: 50px;">
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
      <?php for($i=2015;$i<=2025;$i++): ?>
      { year: '{{ $i }}', value: {{ $cantidad($i) }} },
      <?php endfor; ?>
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
      <?php for($i=2015;$i<=2025;$i++): ?>
      { year: '{{ $i }}', value: {{ $total($i) }} },
      <?php endfor; ?>
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