$(function(){

	var urlInventario = $('#ur').attr('name');
    var url = $('#u').attr('name');

    $('#ur').remove();
    $('#u').remove();

    $('#data-table-movimientos').dataTable();

    $('.rad-list-categ').change(function(){
        FiltrarporTrimestre($(this).val());
    });

    function FiltrarporTrimestre(op){
    	$.ajax({
    		url: urlInventario + '/filtrarportrimestre/' + op,
    		type: 'post',
    		dataType: 'json',
    		beforeSend:function(){
    			$('div#mensaje').html('<img src="'+url+'/assets/products_img/load.GIF">');
    		},
    		success:function(data){
    			$('div#mensaje').html('');
    			$('tbody#movimientos').html('');
    			var movimientos = '';
    			for(var i in data){
    				movimientos += '<tr>';
    				movimientos += '<td>'+(Number(i)+1)+'</td>';
    				movimientos += '<td>'+data[i].FechaMovimiento+'</td>';
    				movimientos += '<td>'+data[i].NombreProducto+'</td>';
    				movimientos += '<td>'+data[i].CantidadMovimiento+'</td>';
    				movimientos += '<td>'+data[i].PrecioMovimiento+'</td>';
    				movimientos += '<td>'+data[i].ImporteMovimiento+'</td>';
    				movimientos += '<td>'+data[i].TipoMovimiento+'</td>';
    				movimientos += '<td>'+data[i].persona+'</td>';
    				movimientos += '<td>'+data[i].RegistradoPor+'</td>';
    				movimientos += '</tr>';
    			}
    			$('tbody#movimientos').html(movimientos);
    			setTimeout(function(){
                    $('#data-table-movimientos').dataTable();
                }, 1000);
    		},
    		error:function(){
    			alert('Error');
    		}
    	});
    }

});
