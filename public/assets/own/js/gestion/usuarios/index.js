var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

function data_table_usuarios(){
    $('#data_table_usuarios').dataTable({
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
            "emptyTable": "No hay usuarios registrados aún.",
            "zeroRecords": "No se encontraron usuarios."
        }
    });
}

$(function(){
    data_table_usuarios();
    $('#url_actual').remove();
    $('#generar_url').remove();

    $('#form_reg_us').submit(function(){
        $.post(generar_url + '/gestion/usuarios/registrar', $(this).serialize(), function(response){
            apprise(response, {'animate':true});
            $('#tb_us').load(url_actual + ' #tb_us_act');
            $('#nuevo_usuario').modal('hide');
            setTimeout(function(){
                data_table_usuarios();
            }, 2000);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

    $('#form_editar_usuario').submit(function(){
        $.post(generar_url + '/gestion/usuarios/actualizar', $(this).serialize(), function(response){
            apprise(response, {'animate':true});
            $('#tb_us').load(url_actual + ' #tb_us_act');
            $('#editar_usuario').modal('hide');
            setTimeout(function(){
                data_table_usuarios();
            }, 2000);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

    $('#tipo_usuario').change(function(){
        if($(this).val() == 2){
            $('#hide_form_group').fadeIn(400);
        } else {
            $('#hide_form_group').fadeOut(400);
        }
    });

});

function editar_usuario(id, tipo_usuario, distrito){
    $('#gif_editar_usuario').show();
    // $('#form_editar_usuario')[0].reset();
    $('#select_ed_usuario').html('');
    $('#select_us_distrito').html('');

    $.get(generar_url + '/gestion/usuarios/editar', 'id=' + id, function(response){
        $('#ed_us_nombre').val(response.Nombres).focus();
        $('#ed_us_aps').val(response.Apellidos);
        $('#ed_us_dni').val(response.DNI);
        $('#ed_us_telefono').val(response.Telefono);
        $('#ed_us_celular').val(response.Celular);
        $('#ed_us_email').val(response.email);
        $('#ed_us_direccion').val(response.Direccion);
        $('#ed_us_usuario').val(response.Usuario);
    }).done(function(){
        var select_distritos = '';
        for(key in distritos){
            var value = distritos[key];
            if(distrito == key){
                select_distritos += '<option selected="selected" value="' + key + '">' + value + '</option>';
            } else {
                select_distritos += '<option value="' + key + '">' + value + '</option>';
            }
        }
        $('#select_us_distrito').html(select_distritos);

        var select_tipos = '';
        for(key in tipos_usuario){
            var value = tipos_usuario[key];
            if(tipo_usuario == key){
                select_tipos += '<option selected="selected" value="' + key + '">' + value + '</option>';
            } else {
                select_tipos += '<option value="' + key +'">' + value + '</option>';
            }
        }
        $('#select_ed_usuario').html(select_tipos);

        $('#gif_editar_usuario').hide();
    }, 'json').fail(function(){
        apprise('Error inesperado, intente nuevamente.');
        $('#gif_editar_usuario').hide();
        $('#editar_usuario').modal('hide');
    });
}

function eliminar_usuario(id){
    apprise('¿Está seguro que desea eliminar este usuario?', {'verify':true, 'animate':true}, function(r){
        if(r){
            $.post(generar_url + '/gestion/usuarios/eliminar', 'id=' + id, function(response){
                apprise(response, {'animate':true});
                $('#tb_us').load(url_actual + ' #tb_us_act');
                setTimeout(function(){
                    data_table_usuarios();
                }, 2000);
            }).fail(function(){
                apprise('Error inesperado, intente nuevamente.');
            });
        }
    });        
}