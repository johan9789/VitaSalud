var url_actual = $('#url_actual').val();
var generar_url = $('#generar_url').val();

function data_table_proveedores(){
    $('#data_table_proveedores').dataTable({
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
            "emptyTable": "No hay proveedores registrados aún.",
            "zeroRecords": "No se encontraron proveedores."
        }
    });
}

$(function(){
    data_table_proveedores();

    $(document).on('submit', '#form_reg_prov', function(){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response);
            $('#nuevo_proveedor').modal('hide');
            $('#form_reg_prov')[0].reset();
            $('#div_proveedores').load(url_actual + ' #div_proveedores_act');
            setTimeout(function(){
                data_table_proveedores();
            }, 2000);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

    $(document).on('submit', '#form_ed_prov', function(){
        $.ajax({
            type: 'patch',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'html'
        }).done(function(response){
            apprise(response);
            $('#editar_proveedor').modal('hide');
            $('#form_ed_prov')[0].reset();
            $('#div_proveedores').load(url_actual + ' #div_proveedores_act');
            setTimeout(function(){
                data_table_proveedores();
            }, 2000);
        }).fail(function(error){
            apprise('Error inesperado, intente nuevamente.');
            console.log(error);
        });
        return false;
    });

});

function editar_proveedor(proveedor, distrito){
    $('#gif_editar_proveedor').show();
    $('#sel_distritos').html('');
    $.get(generar_url + '/gestion/proveedores/dist/' + proveedor, {'type': 'json'}, function(data){
        $('#txt_ruc').val(data.RUC).focus();
        $('#txt_razon_social').val(data.razon_social_proveedor_dist);
        $('#txt_direccion_proveedor').val(data.direccion_proveedor_dist);
        $('#txt_telefono_proveedor').val(data.telf_proveedor_dist);
        $('#txt_email_proveedor').val(data.email_proveedor_dist);
        $('#form_ed_prov').attr('action', generar_url + '/gestion/proveedores/dist/' + proveedor);
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente.');
    });
    $.get(generar_url + '/gestion/proveedores/dist/' + proveedor, {'type': 'jsdist'}, function(data){
        var select = '';
        $.each(data, function(i, e){
            if(i == distrito){
                select += '<option selected="selected" value="' + i + '">' + e + '</option>';
            } else {
                select += '<option value="' + i + '">' + e + '</option>';
            }
        });
        $('#sel_distritos').html(select);
        $('#gif_editar_proveedor').hide();
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente.');
    });
}

function eliminar_proveedor(proveedor){
    apprise('¿Está seguro de eliminar este proveedor?', {'animate':true, 'verify':true}, function(question){
        if(question){
            $.ajax({
                url: generar_url + '/gestion/proveedores/adm/' + proveedor,
                type: 'delete',
                dataType: 'html'
            }).done(function(response){
                apprise(response);
                $('#div_proveedores').load(url_actual + ' #div_proveedores_act');
                setTimeout(function(){
                    data_table_proveedores();
                }, 1000);
            }).fail(function(){
                apprise('Error inesperado, intente nuevamente.');
            });
        }
    });
}