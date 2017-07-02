function actualizar_listas(){
    $('#tb_ped_pen').load($('#url_actual').val() + ' #tb_ped_pen_act');
    $('#tb_ped_conf').load($('#url_actual').val() + ' #tb_ped_conf_act');
    $(this).blur();
}

function detalle_pedido(pedido, tipo){
    var id_tbody = (tipo == '4')?'pedidos_rechazados':'pedidos';
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
                table_content += '<td>' + response[i].NombreProducto + '</td>';
                table_content += '<td>' + Number(response[i].PrecDistribuidor).toFixed(2) + '</td>';
                table_content += '<td>' + response[i].Cantidad + '</td>';
                table_content += '<td>' + Number(response[i].Valor).toFixed(2) + '</td>';
                if(tipo == '4')
                    table_content += '<td style="color:red;"><b>' + response[i].disponible + '</b></td>';
                table_content += '</tr>';
            }
            $('#'+id_tbody).html(table_content);
        },
        error: function(){
            apprise('Error.');
            $('.loader').html('');
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

function eliminar_pendiente(pedido){
    apprise('¿Está seguro de eliminar el pedido?', {'verify':true, 'animate':true}, function(r){
        if(r){
            $.ajax({
                data: 'pedido=' + pedido,
                url: $('#url_el_ped_pen').val(),
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

function agregar_inventario(pedido){
    apprise('¿Está seguro de agregar pedido al inventario?', {'verify':true, 'animate':true}, function(r){
        if(r){
            $.ajax({
                data: 'pedido=' + pedido,
                url: $('#url_agregar_inv').val(),
                type: 'POST',
                dataType: 'html',
                beforeSend:function(){
                    $('#preload_'+pedido).html('<img src="'+$('#url_general').val()+'/assets/img/load.GIF">');
                },
                success: function(response){
                    $('#preload_'+pedido).html('');
                    apprise(response, {'animate':true});
                    $('#tb_ped_pen').load($('#url_actual').val() + ' #tb_ped_pen_act');
                    $('#tb_ped_conf').load($('#url_actual').val() + ' #tb_ped_conf_act');
                },
                error: function(){
                    apprise('Error.');
                    $('#preload_'+pedido).html('');
                }
            });
        }
    });    
}