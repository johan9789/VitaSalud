var urlInventario = $('#ur').attr('name');
var url = $('#u').attr('name');

$(function(){
	data_table_Inventario('#data_table_inventario');

	$('.dt-inv').click(function(){
		var dataID = $(this).attr('data-id');
		var dataOrden = $(this).attr('data-orden');
		var nombreProducto = $(this).parent().parent().find('td:eq(1)').html();
		$('#title-det').html(nombreProducto);
		$('#title-det-group').html(nombreProducto);

		if(dataOrden == 1){
			$('.dt-inv').attr('data-orden', '1');
			$('#div_tb_inventario').html('');
			listar_detalle_stock(dataID);
			$(this).attr('data-orden', '0');
		}
		
	});

	$('#ocultar_mens_ini').click(function(){
		$('#mensaje_inicio').fadeOut(1500, 'linear');
	});

	$(document).on('dblclick', '.editar_prec', function(){
		var precio = $(this).html();
		$(this).html('<input class="form-control txt_prec_det" type="number" min="0.01" max="999999.99" step="0.01" value="' + precio + '" data-valor="' + precio + '" style="color: #000; text-align: center">');
		$(this).find('input').focus().val('').val(precio);
		$(this).removeClass("editar_prec");
		if($(this).attr('data-orden') == 0){
			var dataEdit = 0;
			if($(this).index()%2 == 0){
				$(this).attr('data-orden','1');
				$(this).next().attr('data-orden','1');
				dataEdit = 1;
			} else {
				$(this).attr('data-orden','1');
				$(this).prev().attr('data-orden','1');
				dataEdit = 3;
			}
			$(this).parent().find('td:last').after('<td class="col-lg-1">'
												+ '<a href="#" data-toggle="modal" class="btn btn-metis-6 btn-sm btn-circle guardar_det" data-original-title="Guardar" title="Guardar" data-edit="'+dataEdit+'"><i class="glyphicon glyphicon-floppy-save"></i></a>'
												+ '  <a href="#" data-toggle="modal" class="btn btn-metis-1 btn-sm btn-circle cancelar_det" data-original-title="Cancelar" title="Cancelar" data-edit="'+dataEdit+'"><i class="glyphicon glyphicon-remove"></i></a></td>');
		} else {
			$(this).parent().find('td:last').children().attr('data-edit', '2');
		}
	});

	$(document).on('click', '.cancelar_det', function(){
		var camposCancelar = $(this).attr('data-edit'); // 1: solo primerCampo 2: ambos campos 3: solo segundoCampo
		if(camposCancelar == 2){
			var precioOriginal1 = $(this).parent().prev().prev().children().attr('data-valor');
			var precioOriginal2 = $(this).parent().prev().children().attr('data-valor');
		} else if(camposCancelar == 1){
			var precioOriginal1 = $(this).parent().prev().prev().children().attr('data-valor');
			var precioOriginal2 = $(this).parent().prev().html();
		} else if(camposCancelar == 3){
			var precioOriginal1 = $(this).parent().prev().prev().html();
			var precioOriginal2 = $(this).parent().prev().children().attr('data-valor');
		} else {
			var precioOriginal1 = 'Error';
			var precioOriginal2 = 'Error';
		}
		$(this).parent().prev().prev().html(precioOriginal1);
		$(this).parent().prev().html(precioOriginal2);
		$(this).parent().prev().prev().addClass('editar_prec');
		$(this).parent().prev().addClass('editar_prec');
		$(this).parent().prev().prev().attr('data-orden', '0');
		$(this).parent().prev().attr('data-orden', '0');
		$(this).parent().remove();
	});

	$(document).on('click', '.guardar_det', function(){
		var btn = $(this);
		var tr_edit = $(this).parent().parent();
		var idI = tr_edit.find('td:eq(0)').children('input').val().split('/')[0];
		var idP = tr_edit.find('td:eq(0)').children('input').val().split('/')[1];

		var camposActualizar = $(this).attr('data-edit'); // 1: solo primerCampo 2: ambos campos 3: solo segundoCampo

		if(camposActualizar == 2){
			var precD = $(this).parent().prev().prev().children().val();
			var precP = $(this).parent().prev().children().val();
		} else if(camposActualizar == 1){
			var precD = $(this).parent().prev().prev().children().val();
		} else if(camposActualizar == 3){
			var precP = $(this).parent().prev().children().val();
		} else{
			var precD = 'Error';
			var precP = 'Error';
		}

		if(validarCampos() == 0){
			actualiza_detalle(idI, idP, precD, precP, camposActualizar, btn);
		}
	});

	$(document).on('click', '#link_Agrupar', function(){
		$('#hd_id').val($(this).attr('data-id'));
	});

	$('#frm_agrupar_exist').submit(function(event){
		event.preventDefault();
	});

	$('#btn_agrupar').click(function(){
		var btn = $(this);
		var id = $('#hd_id').val();
		agrupar_detalle(btn, id);
	});

});

function listar_detalle_stock(id){
	$.ajax({
		url: urlInventario + '/detalle-inventario/' + id,
		type: 'get',
		dataType: 'json',
		beforeSend:function(){
			$('#loader').html('<img src="'+url+'/assets/img/load.GIF">');
		}
	}).done(function(data){
		if(data.length == 0){
            $('#div_tb_inventario').html('No existen datos actualmente');
		} else {
			var htmlTabla = '<table id="data_table_detalle" class="table table-bordered table-condensed table-hover table-striped">';
				htmlTabla += '<thead>'
							 + '<tr>'
							 	+ '<th>Existencia</th>'
							 	+ '<th>Costo</th>'
							 	+ '<th>Prec. Distrib</th>'
							 	+ '<th>Prec. Público</th>'
							 + '</tr>'
							+'</thead>';
				htmlTabla += '<tbody>';
					var primeraFila = 0;
					for(var i in data){
						if(primeraFila == 0){
							htmlTabla += '<tr>'
										+ '<td style="background-color: #5cb85c; color: #fff;" class="col-lg-1">'+data[i].Existencia+'<input type="hidden" value="'+data[i].idInventario+'/'+data[i].idProducto+'"></td>'
									 	+ '<td style="background-color: #5cb85c; color: #fff;" class="col-lg-1">'+Number(data[i].Costo).toFixed(2)+'</td>'
									 	+ '<td style="background-color: #5cb85c; color: #fff; cursor:pointer" class="col-lg-1 editar_prec" data-orden="0">'+Number(data[i].PrecDistribuidor).toFixed(2)+'</td>'
									 	+ '<td style="background-color: #5cb85c; color: #fff; cursor:pointer" class="col-lg-1 editar_prec" data-orden="0">'+Number(data[i].PrecPublico).toFixed(2)+'</td>'
								 	+'</tr>';
						} else {
							htmlTabla += '<tr>'
										+ '<td class="col-lg-1">'+data[i].Existencia+'<input type="hidden" value="'+data[i].idInventario+'/'+data[i].idProducto+'"></td>'
									 	+ '<td class="col-lg-1">'+Number(data[i].Costo).toFixed(2)+'</td>'
									 	+ '<td class="col-lg-1 editar_prec" data-orden="0" style="cursor:pointer;">'+Number(data[i].PrecDistribuidor).toFixed(2)+'</td>'
									 	+ '<td class="col-lg-1 editar_prec" data-orden="0" style="cursor:pointer;">'+Number(data[i].PrecPublico).toFixed(2)+'</td>'
								 	+'</tr>';	
						}
						primeraFila++;
					}
				if(primeraFila > 1){
					htmlTabla += '<tr>'
									+ '<td colspan="3">Puede agrupar todas las existencias a un solo precio:</td>'
									+ '<td class="col-lg-1">'
										+'<a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-primary btn-sm" href="#agrupar_detalle_inv" data-id="'+data[i].idProducto+'" id="link_Agrupar">'
											+'<i class="glyphicon glyphicon-qrcode"></i> Agrupar'
                                        +'</a></td>'
								+'</tr>';
				}
				htmlTabla += '</tbody>';
				htmlTabla += '</table>';
				htmlTabla += '<div><span class="label label-success">Fila con precio Actual</span></div>';
			$('#div_tb_inventario').html(htmlTabla);
			$('#loader').html('');
		}
	}).fail(function(){
		apprise('Error inesperado, intente nuevamente.');
		$('#loader').html('');
	});
}

function actualiza_detalle(idI, idP, precD, precP, camposActualizar, btn){
	var idI = idI,
		idP = idP,
		precD = precD || '', 
		precP = precP || '';

	var dataEnv = {};
		dataEnv['idI'] = idI;
		dataEnv['idP'] = idP;
		dataEnv['precD'] = precD;
		dataEnv['precP'] = precP;
		dataEnv['num_c'] = camposActualizar;

	$.ajax({
		url: urlInventario + '/actualizar-detalle',
		type: 'post',
		data: dataEnv,
		beforeSend: function(){
			btn.hide();
			btn.next().hide();
			btn.next().after('<img src="'+url+'/assets/img/load2.GIF">');
		}
	}).done(function(data){
		if(data.errors == 1){
			apprise(data.mensaje);
			if(camposActualizar == 2){
				var precioNuevo1 = precD;
				var precioNuevo2 = precP;
			} else if(camposActualizar == 1){
				var precioNuevo1 = precD;
				var precioNuevo2 = btn.parent().prev().html();
			} else if(camposActualizar == 3){
				var precioNuevo1 = btn.parent().prev().prev().html();
				var precioNuevo2 = precP;
			} else {
				var precioNuevo1 = 'Error';
				var precioNuevo2 = 'Error';
			}
			btn.parent().prev().prev().html(Number(precioNuevo1).toFixed(2));
			btn.parent().prev().html(Number(precioNuevo2).toFixed(2));
			btn.parent().prev().prev().addClass('editar_prec');
			btn.parent().prev().addClass('editar_prec');
			btn.parent().prev().prev().attr('data-orden', '0');
			btn.parent().prev().attr('data-orden', '0');
			btn.parent().remove();
		} else {
			apprise(data.mensaje);
			btn.show();
			btn.next().show();
		}
		btn.next().next().remove();
	}).fail(function(){
		apprise('Error inesperado, intente nuevamente.');
		btn.show();
		btn.next().show();
		btn.next().next().remove();
	});
}

function agrupar_detalle(btn, id){
	$.ajax({
		url: $('#frm_agrupar_exist').attr('action'),
		type: $('#frm_agrupar_exist').attr('method'),
		data: $('#frm_agrupar_exist').serialize(),
		beforeSend:function(){
			btn.hide();
			btn.after('<img src="'+url+'/assets/img/load2.GIF">');
		}
	}).done(function(data){
		if(data.errors == 1){
			limpiar_form('#frm_agrupar_exist');
			$('#agrupar_detalle_inv').modal('hide');
			$('#div_tb_inventario').html('');
			listar_detalle_stock(id);
			apprise(data.mensaje);
		} else {
			apprise(data.mensaje);
			btn.show();
		}
		btn.next().remove();
	}).fail(function(){
		apprise('Error inesperado, intente nuevamente.');
		btn.show();
		btn.next().remove();
	});
}

// Validamos los campos que no esten completos o con datos incorrectos
function validarCampos(){
    var error = 0;
    $('.txt_prec_det').each(function(i, e){
        if(NumeroDecimal($(e).val()) == false){
            $(e).css({'border' : '1px solid red'});
            error++;
        } else {
            $(e).css({'border' : '1px solid #ccc'});
        }
    });
    return error;
}

// Limpiamos campo de un formulario
function limpiar_form(idForm){
	$(idForm + ' input').each(function(i, e){
		$(e).val('');
	});
}

function data_table_Inventario(idTabla){
    $(idTabla).dataTable({
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
            "emptyTable": "No hay productos registrados aún.",
            "zeroRecords": "No se encontraron productos."
        }
    });
}

// Comparar si un numero es decimal o numero natural
function NumeroDecimal(number){
    return !(number < 0.1 || number > 999999.99 || parseInt(100 * number) != 100 * number);
}
