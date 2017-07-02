function actualizar_listas(){
    $('#tb_ped_pen').load($('#url_actual').val() + ' #tb_ped_pen_act');
    $(this).blur();
}

function detalle_pedido(pedido, tipo, nomDist, fechaI, fechaF){
    var id_tbody = (tipo == 'detalle')?'pedidos':'pedidos_rechazados';
    $.ajax({
        data: 'pedido=' + pedido,
        url: $('#url_det_ped').val(),
        type: 'POST',
        dataType: 'json',
        beforeSend:function(){
            $('.loader').html('<img src="'+$('#url_general').val()+'/assets/img/load.GIF">');
        },
        success: function(response){
            $('.loader').html('');
            $('#'+id_tbody).html('');
            var table_content = '';
            for(i in response){
                table_content += '<tr>';
                table_content += '<td>' + '<input name="idprod[]" type="hidden" value="'+response[i].idProducto+'">' + response[i].NombreProducto + '</td>';
                table_content += '<td>' + Number(response[i].PrecDistribuidor).toFixed(2) + '</td>';
                table_content += '<td>' + response[i].Cantidad + '</td>';
                table_content += '<td>' + Number(response[i].Valor).toFixed(2) + '</td>';
                if(Number(response[i].Cantidad) > Number(response[i].Existencia))
                table_content += '<td style="color:red;"><b>' + response[i].Existencia + '</b></td>';
                else
                table_content += '<td>' + response[i].Existencia + '</td>';
                if(tipo == 'rechazar')
                table_content += '<td>' + '<input name="disponible[]" value="" type="number" class="disponible[]" id="'+response[i].idProducto+'" min="0" max="100" tabindex="1">' + '</td>';
                table_content += '</tr>';
            }
            $('#'+id_tbody).html(table_content);
            $('.nomDist').html(nomDist);
            $('.fechaI').html(fechaI);
            $('.fechaF').html(fechaF);
            if(tipo == 'rechazar')
                //$('<input name="idped" type="hidden" value="'+response[i].idPedido+'">').appendTo($('#'+id_tbody).parent().parent());
                $('#id_pedido').attr('value', response[0].idPedido );
        },
        error: function(){
            apprise('Error.');
            $('.loader').html('');
        }
    });
}

function detalle_pedido_confirmado(pedido, nomDist, fechaI, fechaF){
    $.ajax({
        data: 'pedido=' + pedido,
        url: $('#url_det_ped').val(),
        type: 'POST',
        dataType: 'json',
        beforeSend:function(){
            $('.loader').html('<img src="'+$('#url_general').val()+'/assets/img/load.GIF">');
        },
        success: function(response){
            $('.loader').html('');
            $('#pedidos_confirmados').html('');
            var table_content = '';
            for(i in response){
                table_content += '<tr>';
                table_content += '<td>' + response[i].NombreProducto + '</td>';
                table_content += '<td>' + Number(response[i].PrecDistribuidor).toFixed(2) + '</td>';
                table_content += '<td>' + response[i].Cantidad + '</td>';
                table_content += '<td>' + Number(response[i].Valor).toFixed(2) + '</td>';
                table_content += '</tr>';
            }
            $('#pedidos_confirmados').html(table_content);
            $('.nomDist').html(nomDist);
            $('.fechaI').html(fechaI);
            $('.fechaF').html(fechaF);
        },
        error: function(){
            $('.loader').html('');
            apprise('Error.');
        }
    });
}    

function confirmar_pendiente(pedido){
    apprise('¿Está seguro de confirmar el pedido?', {'verify':true, 'animate':true}, function(r){
        if(r){
            $.ajax({
                data: 'pedido=' + pedido,
                url: $('#url_conf_ped_pen').val(),
                type: 'POST',
                dataType: 'html',
                success: function(response){
                    $('#tb_ped_pen').load($('#url_actual').val() + ' #tb_ped_pen_act');
                    $('#tb_ped_conf').load($('#url_actual').val() + ' #tb_ped_conf_act');
                    apprise(response, {'animate':true});
                },
                error: function(){
                    apprise('Error.');
                }
            });
        }
    });    
}

function modificar_pendiente(pedido){
    apprise('En proceso...');
}

function ocultar_pedido(pedido){
    apprise('¿Está seguro de ocultar el pedido?', {'verify':true, 'animate':true}, function(r){
        if(r){
            $.ajax({
                data: 'pedido=' + pedido,
                url: $('#url_oc_ped_conf').val(),
                type: 'POST',
                dataType: 'html',
                success: function(response){
                    apprise(response, {'animate':true});
                    $('#tb_ped_pen').load($('#url_actual').val() + ' #tb_ped_pen_act');
                    $('#tb_ped_conf').load($('#url_actual').val() + ' #tb_ped_conf_act');
                },
                error: function(){
                    apprise('Error.');
                }
            });
        }
    });    
}
