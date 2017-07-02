var generar_url = $('#generar_url').val();
var url_actual = $('#url_actual').val();

function data_table_clientes(){
    $('#data_table_clientes').dataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, -1], 
            [5, 10, "Todos"]
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

function data_table_empresas(){
    $('#data_table_empresas').dataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10, -1], 
            [5, 10, "Todos"]
        ],
        "language": {
            "search" : "Buscar:",
            "paginate": {               
                "previous": "Anterior",
                "next": "Siguiente"
            },
            "emptyTable": "No hay empresas registradas aún.",
            "zeroRecords": "No se encontraron empresas."
        }
    });
}

$(function(){
    data_table_clientes();
    data_table_empresas();

    $(document).on('click', '#spn_cli_selec', function(){
    	$('#spn_cli_selec').html('').hide();
		$('#hd_cliente_seleccionado').val('');
    });

    $(document).on('submit', '#form_reg_cli', function(){
        $.post($(this).attr('action'), $(this).serialize(), function(data){
            apprise('Cliente registrado');
            clean_form($('#form_reg_cli'));
            $('#em_cli').val('');
            $('#registrar_cliente').modal('hide');
            $('#btn_rg_cli').blur();
            $('#div_tb_clientes').load(url_actual + ' #div_tb_clientes_act');
            setTimeout(function(){
                data_table_clientes();
            }, 1500);
            seleccionar_cliente(data);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente');
        });
        return false;
    });

    $(document).on('submit', '#form_reg_emp', function(){
        $.post($(this).attr('action'), $(this).serialize(), function(data){
            apprise('Empresa registrada');
            clean_form($('#form_reg_emp'));
            $('#em_cli').val('');
            $('#registrar_empresa').modal('hide');
            $('#btn_rg_emp').blur();
            $('#div_tb_empresas').load(url_actual + ' #div_tb_empresas_act');
            setTimeout(function(){
                data_table_empresas();
            }, 1500);
            seleccionar_empresa(data);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente');
        });
        return false;
    });
    
    $(document).on('keypress', '.txt_codigo', function(e){
        if(e.which == 13){
            var cod = $(this).attr('data');
            $.post(generar_url + '/ventas/productos', {codigo : $(this).val()}, function(data){
                if(data == 'Producto no encontrado.'){
                    $('#txt_codigo_' + cod).val('');
                    $('#txt_codigo_' + cod).attr('placeholder', data);
                } else {
                    $('#txt_codigo_' + cod).removeAttr('placeholder');
                    var nombre = data.NombreProductoDist;
                    var precio = data.PrecPublico;
                    $('#txt_producto_' + cod).val(nombre);                    
                    $('#txt_precio_' + cod).val(precio);
                    $('#txt_cantidad_' + cod).val(1).focus().removeAttr('readonly');
                    $('#txt_total_' + cod).val(precio * 1);

                    var row_count = $("#tbd_ventas tr").length;
                    
                    var suma = 0;
                    for(var i=1;i<=row_count;i++){
                        var subtotal = Number($('#txt_total_' + i).val());
                        suma += subtotal;
                    }
                    $('#total_final').val(suma);
                    suma = 0;

                    var otra_suma = 0;
                    for(var i=1;i<=row_count;i++){
                        var subtotal = Number($('#txt_cantidad_' + i).val());
                        otra_suma += subtotal;
                    }                    
                    $('#cantidad_final').val(otra_suma);
                    otra_suma = 0;
                }
            }, 'json').fail(function(error){
                apprise('Error inesperado, intente nuevamente.');
                console.log(error);
            });
        }
    });

    $(document).on('keyup', '.txt_cantidad', function(){
        var cod = $(this).attr('data');
        var cantidad = $('#txt_cantidad_' + cod).val();
        var precio = $('#txt_precio_' + cod).val();
        var total = cantidad * precio;
        $('#txt_total_' + cod).val(total);       
        
        var row_count = $("#tbd_ventas tr").length;
        
        var suma = 0;
        for(var i=1;i<=row_count;i++){
            var subtotal = Number($('#txt_total_' + i).val());
            suma += subtotal;
        }
        $('#total_final').val(suma);
        suma = 0;

        var otra_suma = 0;
        for(var i=1;i<=row_count;i++){
            var subtotal = Number($('#txt_cantidad_' + i).val());
            otra_suma += subtotal;
        }                    
        $('#cantidad_final').val(otra_suma);
        otra_suma = 0;
    });

    var row_ventas = $('#fila_ventas_1').clone();

    $(document).on('keypress', '.txt_cantidad', function(e){
        if(e.which == 13){
            if($(this).val() != 0){
                var eq_td = 0;

                var cod = $(this).attr('data');
                nuevo_cod = Number(cod) + 1;

                $(this).blur();
                                
                $(row_ventas).clone().appendTo('#tbd_ventas').show().attr('id', 'fila_ventas_' + nuevo_cod);
                $('#fila_ventas_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_codigo_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_ventas_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_producto_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_ventas_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_precio_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_ventas_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_cantidad_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_ventas_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_total_' + nuevo_cod,
                    data: nuevo_cod,
                });
                $('#txt_codigo_' + nuevo_cod).focus();
            }
        }
    });

    $(document).on('submit', '#form_ventas', function(){
        return false;    
    });

    $(document).on('click', '#btn_finalizar_venta', function(){
        if($('#hd_cliente_seleccionado').val() != ''){
            apprise('¿Está seguro que desea realizar la venta?', {'animate': true, 'verify': true}, function(answer){
                if(answer){
                    $.post($('#form_ventas').attr('action'), $('#form_ventas').serialize(), function(response){
                        if(response == 'Venta realizada'){
                            $('#dv_ventas').load(url_actual + ' #dv_ventas_act');
                        }                        
                    }).fail(function(response){
                        console.log(response);
                    }); 
                }
            });            
        } else {
            apprise('Selecciona un cliente.');
        } 
    });

    $(document).on('click', '#btn_cancelar_venta', function(){
        apprise('¿Está seguro que desea cancelar la venta?', {'animate': true, 'verify': true}, function(answer){
            if(answer){
                $('#dv_ventas').load(url_actual + ' #dv_ventas_act');
                setTimeout(function(){
                    $('.txt_codigo').focus();
                }, 1000);
            }
        });
    });

});

function clean_form(form){
    // recorremos todos los campos que tiene el formulario
    $(':input', form).each(function(){
        var type = this.type;
        var tag = this.tagName.toLowerCase();
        // limpiamos los valores de los campos…
        if(type == 'text' || type == 'password' || tag == 'textarea'){
            this.value = "";
            // excepto de los checkboxes y radios, le quitamos el checked
            // pero su valor no debe ser cambiado
        } else if(type == 'checkbox' || type == 'radio'){
            this.checked = false;
            // los selects le ponesmos el indice a -
        } else if(tag == 'select'){
            this.selectedIndex = -1;
        }
    });
}

function seleccionar_cliente(cliente){
    $.get(generar_url + '/clientes/' + cliente, {type : 'json'}, function(data){
		$('#spn_cli_selec').show().html('Cliente seleccionado: ' + data.Nombres + ' ' + data.Apellidos);
		$('#hd_cliente_seleccionado').val(data.idCliente);
		$('#seleccionar_cliente').modal('hide');
	}).fail(function(){
		apprise('Error inesperado, intente nuevamente');
	});	
}

function seleccionar_empresa(empresa){
    $.get(generar_url + '/empresas/' + empresa, {type : 'json'}, function(data){
        $('#spn_cli_selec').show().html('Empresa seleccionada: ' + data.NombreEmpresa);
        $('#hd_cliente_seleccionado').val(data.idCliente);
        $('#seleccionar_empresa').modal('hide');
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente');
    }); 
}

function ver_cliente(cliente){
    $.get(generar_url + '/clientes/' + cliente, {type : 'json'}, function(data){
        $('#spn_ver_cli_nombre').val(data.Nombres);
        $('#spn_ver_cli_app').val(data.Apellidos);
        $('#spn_ver_cli_dni').val(data.DNI);
        $('#spn_ver_cli_tel').val(data.Telefono);
        $('#spn_ver_cli_cel').val(data.Celular);
        $('#spn_ver_cli_email').val(data.email);
        $('#spn_ver_cli_dir').val(data.Direccion);
        $('#spn_ver_cli_dist').val(data.NombreDistrito);
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente');
    }); 
}

function ver_empresa(empresa){
    $.get(generar_url + '/empresas/' + empresa, {type : 'json'}, function(data){
        $('#spn_ver_emp_ruc').val(data.RUC);
        $('#spn_ver_emp_nom').val(data.NombreEmpresa);
        $('#spn_ver_emp_dir').val(data.Direccion);
        $('#spn_ver_emp_tel').val(data.Telefono);
        $('#spn_ver_emp_email').val(data.Email);
        $('#spn_ver_emp_dist').val(data.NombreDistrito);
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente');
    }); 
}