var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

$(function(){	

	$(document).on('submit', '#form_actualizar_acceso', function(e){
		$.post($(this).attr('action'), $(this).serialize(), function(response){
			apprise(response);
			$('#modificar_acceso_general').modal('hide');
			actualizar_lista_accesos();
		}).fail(function(){
			apprise('Error inesperado, intente nuevamente');
			$('#modificar_acceso_general').modal('hide');
			$('#form_actualizar_acceso')[0].reset();			
		});
		e.preventDefault();
	});

	$(document).on('submit', '#form_asignar_acceso', function(e){
		$.ajax({
			type: 'post',
			data: $(this).serialize(),
			url: $(this).attr('action'),
			beforeSend: function(){
				$('#gif_loading_3').show();
			}
		}).done(function(){
			$('#gif_loading_3').hide();
		}).done(function(response){
			apprise(response);
			$('#asignar_acceso_general').modal('hide');
		}).fail(function(response){
			apprise('Error inesperado, intente nuevamente');
			$('#asignar_acceso_general').modal('hide');
			$('#gif_loading_3').hide();
			console.log(response);
		});
		e.preventDefault();
	});

});

function editar_acceso_general(acceso){
	$.post(generar_url + '/gestion/usuarios/privilegios/detalle-acceso', {'acceso': acceso}, function(data){
		$('#txt_nombre_acceso').val(data.nombre_acceso);
		$('#txt_descripcion_acceso').val(data.descripcion_acceso);
		if(data.url_acceso === null){
			$('#txt_url_acceso').attr('placeholder', 'No tiene URL, es acceso general.');
		} else {
			$('#txt_url_acceso').val(data.url_acceso);
		}
	}, 'json').fail(function(response){
		apprise('Error inesperado, intente nuevamente');
	});
}

function eliminar_acceso_general(acceso){
	apprise('¿Está seguro de eliminar este acceso general?', {'verify':true, 'animate':true}, function(answer){
		if(answer){
			$.ajax({
				'url': generar_url + '/accesos/' + acceso,
				'type': 'delete',
			}).done(function(response){
				apprise(response, {'animate':true});
				actualizar_lista_accesos();
			}).fail(function(){
				apprise('Error inesperado, intente nuevamente');
			});
		}
	});
}

function asignar_acceso_general(acceso, nombre_acceso){	
	$.ajax({
		type: 'post',
		url: generar_url + '/gestion/usuarios/privilegios/roles-actuales/' + acceso,
		beforeSend: function(){
			$('#txt_nombre_acceso_as').val(nombre_acceso);
			$('#tbd_roles_actuales').html('');
			$('#gif_loading_1').show();			
		}
	}).done(function(){		
		$('#gif_loading_1').hide();
	}).done(function(data){
		var tbody = '';
		$.each(data, function(i, e){
			tbody += '<tr><td>' + e.nombretipo + '</td></tr>';
		});
		$('#tbd_roles_actuales').html(tbody);
	}, 'json').fail(function(response){		
		apprise('Error inesperado, intente nuevamente');
		console.log(response);
	});
	
	$.ajax({
		type: 'post',
		url: generar_url + '/gestion/usuarios/privilegios/roles-restantes/' + acceso,
		beforeSend: function(){
			$('#id_sel_roles_disponibles_as').html('');
			$('#gif_loading_2').show();
		}
	}).done(function(){
		$('#gif_loading_2').hide();
	}).done(function(data){
		var select = '';
		$.each(data, function(i, e){
			select += '<option value="' + e.id_tipousuario + '">' + e.nombretipo + '</option>';
		});
		$('#id_sel_roles_disponibles_as').html(select);
	}, 'json').fail(function(response){		
		apprise('Error inesperado, intente nuevamente');
		console.log(response);
	});

	$('#hd_id_acceso').val(acceso);

}

function actualizar_lista_accesos(){
	$('#tb_acc_gen').load(url_actual + ' #tb_acc_gen_act');
	$('#div_accesos').load(url_actual + ' #div_accesos_act');
}

function ver_sub_acc_gen(acceso){
	$('.sub_acceso_row_' + acceso).fadeIn(500);
	$('#btn_ocultar_sub_acc_gen_' + acceso).show();
	$('#btn_ver_sub_acc_gen_' + acceso).hide();
}

function ocultar_sub_acc_gen(acceso){
	$('.sub_acceso_row_' + acceso).fadeOut(500);
	$('#btn_ocultar_sub_acc_gen_' + acceso).hide();
	$('#btn_ver_sub_acc_gen_' + acceso).show();
}

function ver_mini_acc_gen(acceso){
	$('.mini_acceso_row_' + acceso).fadeIn(500);
	$('#btn_ocultar_mini_acc_gen_' + acceso).show();
	$('#btn_ver_mini_acc_gen_' + acceso).hide();
}

function ocultar_mini_acc_gen(acceso){
	$('.mini_acceso_row_' + acceso).fadeOut(500);
	$('#btn_ocultar_mini_acc_gen_' + acceso).hide();
	$('#btn_ver_mini_acc_gen_' + acceso).show();
}