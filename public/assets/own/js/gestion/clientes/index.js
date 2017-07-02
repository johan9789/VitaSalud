var urlClientes = $('#ur').attr('name');
var url = $('#u').attr('name');
$(function(){
    data_table_Clientes('#dataTableClientesN');
    data_table_Clientes('#dataTableClientesJ');

    $('.rad-list-categ').change(function(){
        if($(this).val() == '1'){
            $('#tabla-clientesJ').show();
            $('#tabla-clientesN').hide();
        }else{
            $('#tabla-clientesJ').hide();
            $('#tabla-clientesN').show();
        }
    });

    $('.editar').click(function(){
        var filaEditar = $(this).parent().parent();

        if($(this).attr('data-id') == 'N'){
            var Nombre = filaEditar.find('td:eq(1)').html();
            var apellidos = filaEditar.find('td:eq(2)').html();
            var dni = filaEditar.find('td:eq(3)').html();
            var telefono = filaEditar.find('td:eq(4)').html();
            var celular = filaEditar.find('td:eq(5)').html();
            var email = filaEditar.find('td:eq(6)').html();
            var direccion = filaEditar.find('td:eq(7)').html();
            var idPersona = $(this).children().attr('data-id');

            $('#ed_clN_nombre').val(Nombre);
            $('#ed_clN_aps').val(apellidos);
            $('#ed_clN_dni').val(dni);
            $('#ed_clN_telefono').val(telefono);
            $('#ed_clN_celular').val(celular);
            $('#ed_clN_email').val(email);
            $('#ed_clN_direccion').val(direccion);
            $('#ed_clN_idPersona').val(idPersona);
        }
        if($(this).attr('data-id') == 'J'){
            var empresa = filaEditar.find('td:eq(1)').html();
            var ruc = filaEditar.find('td:eq(2)').html();
            var telefono = filaEditar.find('td:eq(3)').html();
            var email = filaEditar.find('td:eq(4)').html();
            var direccion = filaEditar.find('td:eq(5)').html();
            var idEmpresa = $(this).children().attr('data-id');

            $('#ed_clJ_empresa').val(empresa);
            $('#ed_clJ_ruc').val(ruc);
            $('#ed_clJ_telefono').val(telefono);
            $('#ed_clJ_email').val(email);
            $('#ed_clJ_direccion').val(direccion);
            $('#ed_clJ_idEmpresa').val(idEmpresa);
        }
    });

    $('#form_editar_cliente').submit(function(event){

        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var dataEnv = $(this).serialize();

        apprise('¿Seguro que desea modificar?', {'verify':true, 'animate':true}, function(r){
          if(r){
              $.ajax({
                url: action,
                type: method,
                data: dataEnv,
                beforeSend:function(){     
                  $('#loaderN').html('<img src="'+url+'/assets/img/load.GIF">');
                }
              }).done(function(data){
                $('#loaderN').html('');
                $('#tabla-clientesN').load(urlClientes + ' #load-clientesN');
                $('#editar_clienteN').modal('hide');
                setTimeout(function(){
                    data_table_Clientes('#dataTableClientesN');
                }, 2000);
                apprise(data);
              }).fail(function(){
                apprise('Error inesperado, intente nuevamente.');
                $('#loaderN').html('');
              });
          }
      });

        event.preventDefault();
    });

    $('#form_editar_empresa').submit(function(event){
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var dataEnv = $(this).serialize();

        apprise('¿Seguro que desea modificar?', {'verify':true, 'animate':true}, function(r){
            if(r){
                $.ajax({
                    url: action,
                    type: method,
                    data: dataEnv,
                    beforeSend:function(){
                        $('#loaderJ').html('<img src="'+url+'/assets/img/load.GIF">');
                    }
                }).done(function(data){
                    $('#loaderJ').html('');
                    $('#tabla-clientesJ').load(urlClientes + ' #load-clientesJ');
                    $('#editar_clienteJ').modal('hide');
                    setTimeout(function(){
                        data_table_Clientes('#dataTableClientesJ');
                    },2000);
                    apprise(data);
                }).fail(function(data){
                    apprise('Error inesperado, intente nuevamente.');
                    $('#loaderJ').html('');
                });
            }
        });

        event.preventDefault();
    });

});

function data_table_Clientes(idTabla){
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
            "emptyTable": "No hay clientes registrados aún.",
            "zeroRecords": "No se encontraron clientes."
        }
    });
}