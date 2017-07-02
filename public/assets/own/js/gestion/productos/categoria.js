$(function(){
	var urlCategoria = $('#ur').attr('name');
    var url = $('#u').attr('name');

	$(document).on('click', '.eliminar', function(){
        // alert($(this).attr('data-id'));
        var idp = $(this).attr('data-id');
        apprise('¿Está seguro de eliminar esta categoria?', {'animate':true, 'verify':true}, function(answer){
            if(answer){
                $.ajax({
                    url: url + "/gestion/productos/eliminarcategoria/" + idp,
                    type: 'post',
                    beforeSend:function(){
                        $('#mensaje').html('<img src="'+url+'/assets/img/load.GIF">');
                    }
                }).done(function(response){
                    //apprise(response);
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
    });

	$(document).on('click', '.editar', function(){
        var idp = $(this).attr('data-id');
        $.post(url + "/gestion/productos/editarcategoria/" + idp, function(data){
            $('#txtcategoria').val(data.NombreCategoriaProducto);
            $('#txtadescripcion').val(data.DescripcionCategoriaProducto);
        }, 'json').fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        })
    });

    $("#form_editar_cat").submit(function(){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response);
            $('#collapse4').load($('#url_actual').val() + ' #data_table_categoria');
            setTimeout(function(){
                $('#data_table_categoria').dataTable();
            }, 1000);  
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        })
        return false;
    });
})