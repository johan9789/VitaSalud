$(function(){

	var urlInventario = $('#ur').attr('name');
    var url = $('#u').attr('name');

    $('#ur').remove();
    $('#u').remove();

    $('#data-table-movimientos').dataTable();

    $('.rad-list-categ').change(function(){
        // alert($(this).val());
        FiltrarporTrimestre($(this).val());
        // $('#data-table-movimientos').dataTable();
    });

    /* $(document).on('change', '.rad-list-categ', function(){
    	FiltrarporTrimestre($(this).val());
    }); */

    function FiltrarporTrimestre(op){

    	$.ajax({

    		url: urlInventario+'/filtrarportrimestre/'+op,
    		type: 'post',
    		dataType: 'json',
    		beforeSend:function(){
    			$('div#mensaje').html('<img src="'+url+'/assets/products_img/load.GIF">');
    		},
    		success:function(data){
    			$('div#mensaje').html('');

    			$('tbody#movimientos').html('');
    			var movimientos = '';
    			for(i in data){
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

/*************Selección de un producto********************/
  $(document).on('change', '.slc_producto', function(){

    id = $(this).val();
    data_id = $(this).attr('data-id');

    completarDatos(id);

  });
/******  Esta función completa los datos del producto soleccionado  *********/
function completarDatos(id){
  if(id == '0'){
    //completamos los demas campos
    $('#codigo').val('');
    $('#codbarras').val('');
    //$('#lbl_verExistencia'+data_id).html('0');
  }else{
    //Activamos las cajas de texto
    $('#codigo').attr('readonly', false);
    $('#codbarras').attr('readonly', false);
    obtenerDatos(id);
  }
}

/*************Función Ajax para listar los campos de inventario***************/
function obtenerDatos(id){
    alert("prueba"); 
  $.ajax({
    url: urlInventario+'/filtrardatos/'+id,
    type: 'post',
    dataType: 'json',
    apprise(id); 
    success:function(data){
      if(data.idProducto == 0){
        $('#codigo').val('');
        $('#codbarras').val('');
      }
      else{
        $('#codigo').val(Number(data.idProducto));
        $('#codbarras').val(Number(data.codbarras));
      }

      alert(data.idProducto);
    },
    
    error:function(){
      apprise('Error');
    }
  });

}