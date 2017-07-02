var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

$(function(){
    data_table_ventas();

    $('#date_venta').change(function(){
        var fecha = $(this).val();
        if(fecha == ''){
            $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes' + ' #dv_rep_ventas_act');
            history.replaceState(null, null, generar_url + '/ventas/adm/reportes');
            $(this).blur();
            setTimeout(function(){
                data_table_ventas();
            }, 1000);
        } else {
            $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes/fecha/' + fecha + ' #dv_rep_ventas_act');
            history.replaceState(null, null, generar_url + '/ventas/adm/reportes/fecha/' + fecha);
            setTimeout(function(){
                data_table_ventas();
            }, 1000);
        }
    }); 

    $('#btn_int_fechas').click(function(){
        var fecha = $('#reservation').val();
        if(fecha == ''){
            $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes' + ' #dv_rep_ventas_act');  
            history.replaceState(null, null, generar_url + '/ventas/adm/reportes');
            $(this).blur();
            setTimeout(function(){
                data_table_ventas();
            }, 1000);
        } else {
            var part_fecha = fecha.split(" - ");
            
            var part_fecha1 = part_fecha[0].split("/");
            var part_fecha2 = part_fecha[1].split("/");

            var fecha1 = part_fecha1[2] + '-' + part_fecha1[1] + '-' + part_fecha1[0];
            var fecha2 = part_fecha2[2] + '-' + part_fecha2[1] + '-' + part_fecha2[0];        
            
            $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes/fechas/' + fecha1 + '/' + fecha2 + ' #dv_rep_ventas_act');
            history.replaceState(null, null, generar_url + '/ventas/adm/reportes/fechas/' + fecha1 + '/' + fecha2);
            setTimeout(function(){
                data_table_ventas();
            }, 1000);
        }
    });

    $('#btn_actualizar_reportes').click(function(){
        $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes' + ' #dv_rep_ventas_act');
        history.replaceState(null, null, generar_url + '/ventas/adm/reportes');
        $(this).blur();
        $('#date_venta').val('');
        setTimeout(function(){
            data_table_ventas();
        }, 1000);
    });

    $('#select_dist').change(function(){
        var dist = $(this).val();
        $('#dv_rp_ventas').load(generar_url + '/ventas/adm/reportes/dist/' + dist + ' #dv_rep_ventas_act');
        history.replaceState(null, null, generar_url + '/ventas/adm/reportes/dist/' + dist);
        setTimeout(function(){
            data_table_ventas();
        }, 1000);
    });

});

function detalle_venta(venta, fecha, hora, cliente){
	$.post(generar_url + '/ventas/adm/reportes/detalle', {venta: venta}, function(data){
        $('#dtd_venta').html('Vendido a: <i>' + cliente + '</i> el (' + fecha + ') a las (' + hora + ')');
		$('#tbd_detalle_venta').html('');
        var detalle = '';
        $.each(data, function(i, e){
            detalle += '<tr>';
            detalle += '<td>' + e.NombreProducto + '</td>';
            detalle += '<td>' + e.PrecioUnit + '</td>';
            detalle += '<td>' + e.Cantidad + '</td>';
            detalle += '<td>' + e.PrecioTotal + '</td>';
            detalle += '</tr>';
        });
        $('#tbd_detalle_venta').html(detalle);
        $('#link_impr_ven').attr('href', generar_url + '/ventas/adm/reportes/detalle/' + venta).click(function(){
            $('#detalle_venta').modal('hide');
        });
        $('#link_des_ven').attr('href', generar_url + '/ventas/adm/reportes/detalle/' + venta + '/download').click(function(){
            $('#detalle_venta').modal('hide');
        });
        $('#link_des_exc_ven').attr('href', generar_url + '/ventas/adm/reportes/excel/' + venta).click(function(){
            $('#detalle_venta').modal('hide');
        });
	}, 'json').fail(function(error){
		apprise('Error inesperado, intente nuevamente.');
        $('#tbd_detalle_venta').html('');
        $('#link_impr_ven').removeAttr('href');
        $('#link_des_ven').removeAttr('href');
        $('#link_des_exc_ven').removeAttr('href');
        $('#detalle_venta').modal('hide');
        console.log(error);
	});
}

function data_table_ventas(){
    $('#data_table_ventas').dataTable({
        "iDisplayLength": 10,
        "aLengthMenu": [
            [10, 25, 40, 50, -1], 
            [10, 25, 40, 50, "Todos"]
        ],
        "language": {
            "search" : "Buscar:",
            "paginate": {               
                "previous": "Anterior",
                "next": "Siguiente"
            },
            "emptyTable": "No hay ventas registradas aún.",
            "zeroRecords": "No se encontraron ventas."
        }
    });
}