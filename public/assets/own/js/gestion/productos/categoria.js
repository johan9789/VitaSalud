var url = $('#u').attr('name');

$(function(){
    $('#data_table_categoria').dataTable();

    $("#form_editar_cat").submit(function(){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response);
            $('#collapse4').load($('#url_actual').val() + ' #data_table_categoria');
            setTimeout(function(){
                $('#data_table_categoria').dataTable();
            }, 1000);  
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

});

function editarCategoria(id){
    $.get(url + "/gestion/productos/editarcategoria/" + id, function(data){
        $('#txtcategoria').val(data.NombreCategoriaProducto);
        $('#txtadescripcion').val(data.DescripcionCategoriaProducto);
    }, 'json').fail(function(){
        apprise('Error inesperado, intente nuevamente.');
    });
}

function eliminarCategoria(id){
    apprise('¿Está seguro de eliminar esta categoria?', {animate: true, verify: true}, function(answer){
        if(answer){
            $.ajax({
                url: url + "/gestion/productos/eliminarcategoria/" + id,
                type: 'post',
                beforeSend:function(){
                    $('#mensaje').html('<img src="'+url+'/assets/img/load.GIF">');
                }
            }).done(function(response){
                $('#collapse4').load($('#url_actual').val() + ' #data_table_categoria');
                $('#mensaje').html('');
                setTimeout(function(){
                    $('#data_table_categoria').dataTable();
                }, 1000);
            }).fail(function(){
                apprise('Error inesperado, intente nuevamente.');
            });
        } else {
            return false;
        }
    });
}
