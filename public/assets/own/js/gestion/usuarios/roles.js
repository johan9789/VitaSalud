var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

function data_table_roles(){
    $('#data_table_roles').dataTable({
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
            "emptyTable": "No hay roles registrados aún.",
            "zeroRecords": "No se encontraron roles."
        }
    });
}

$(function(){
	data_table_roles();

	$(document).on('submit', '#form_reg_rol', function(e){
		apprise('¿Está seguro que desea crear este rol?', {'animate':true, 'verify':true}, function(answer){
			if(answer){
				$.post($('#form_reg_rol').attr('action'), $('#form_reg_rol').serialize(), function(response){
					apprise(response, {'animate':true});
					$('#tb_rol').load(url_actual + ' #tb_rol_act');
					setTimeout(function(){
						data_table_roles();
					}, 2000);
					$('#form_reg_rol')[0].reset();
					$('#nuevo_rol').modal('hide');
				}).fail(function(){
					apprise('Error inesperado, intente nuevamente');
				});
			} else {
				$('#form_reg_rol')[0].reset();
				$('#nuevo_rol').modal('hide');
			}
		})		
		e.preventDefault();
	});

});