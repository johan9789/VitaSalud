var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

$(function(){
	data_table_compras();

	$('#btn_actualizar_reportes').click(function(){
		$(this).blur();
		$('#dv_rep_compras').load(url_actual + ' #dv_rep_compras_act');
		setTimeout(function(){
			data_table_compras();
		}, 1000);		
	});

});

function data_table_compras(){
    $('#data_table_compras').dataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, -1], 
            [5, 10, "Todos"]
        ],
        "language": {
            "search" : "Buscar:",
            "paginate": {               
                "previous": "Anterior",
                "next": "Siguiente"
            },
            "emptyTable": "No hay compras registradas aún.",
            "zeroRecords": "No se encontraron compras."
        }
    });
}

function detalle_compra(compra){
	$.get(generar_url + '/compras/dist/reportes/' + compra, function(data){
		$('#tbd_detalle_compra').html('');
		var detalle = '';
        $.each(data, function(i, e){
            detalle += '<tr>';
            detalle += '<td>' + e.producto.NombreProductoDist + '</td>';
            detalle += '<td>' + e.CostoCompraDist + '</td>';
            detalle += '<td>' + e.CantidadCompraDist + '</td>';
            detalle += '<td>' + e.TotalCostoCompraDist + '</td>';
            detalle += '</tr>';
        });
        $('#tbd_detalle_compra').html(detalle);
	}, 'json').fail(function(response){
		apprise('Error inesperado, intente nuevamente.');
		$('#detalle_compra').modal('hide');
        console.log(response);
	});
}