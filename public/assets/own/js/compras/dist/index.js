var url_actual = $('#url_actual').val();
var row_ventas = $('#fila_compras_1').clone();
var igv = $('#hd_igv').val();

function data_table_proveedores(){
    $('#data_table_proveedores').dataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [
            [5, 10], 
            [5, 10]
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
    $('#hd_igv').remove();
    $('#url_actual').remove();

    data_table_proveedores();

    $(document).on('change', '.select_prod', function(){
        var code = $(this).attr('data');
        if($(this).val() != 0){
            $('#txt_costo_' + code).removeAttr('disabled').focus();
            $('#txt_cantidad_' + code).removeAttr('disabled');
            $('#btn_agregar_compras').removeAttr('disabled');
        }
    });

    $(document).on('click', '#btn_agregar_compras', function(){
        var cod = $('#tbd_compras tr:last').attr('data');
        var nuevo_cod = Number(cod) + 1;

        if($('.select_prod:last').val() != 0 && $('.txt_costo:last').val() != 0 && $('.txt_cantidad:last').val() != 0){
            $(row_ventas).clone().appendTo('#tbd_compras').show().attr('id', 'fila_compras_' + nuevo_cod).attr('data', nuevo_cod);

            var eq_td = 0;
            
            $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                id: 'select_prod_' + nuevo_cod,
                data: nuevo_cod,
            });
            eq_td++;
            $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                id: 'txt_costo_' + nuevo_cod,
                data: nuevo_cod,
            });
            eq_td++;
            $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                id: 'txt_cantidad_' + nuevo_cod,
                data: nuevo_cod,
            });        
            eq_td++;
            $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                id: 'lbl_total_' + nuevo_cod,
                data: nuevo_cod,
            });   

            $(this).attr('disabled', 'disabled');
            $('.select_prod:last').focus();
        }        
        
        $(this).blur();
    });

    $(document).on('keypress', '.txt_cantidad', function(e){
        if(e.which == 13){
            if($('.select_prod:last').val() != 0 && $('.txt_costo:last').val() != 0 && $('.txt_cantidad:last').val() != 0){
                var cod = $('#tbd_compras tr:last').attr('data');
                var nuevo_cod = Number(cod) + 1;

                $(row_ventas).clone().appendTo('#tbd_compras').show().attr('id', 'fila_compras_' + nuevo_cod).attr('data', nuevo_cod);

                var eq_td = 0;
                
                $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'select_prod_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_costo_' + nuevo_cod,
                    data: nuevo_cod,
                });
                eq_td++;
                $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'txt_cantidad_' + nuevo_cod,
                    data: nuevo_cod,
                });        
                eq_td++;
                $('#fila_compras_' + nuevo_cod + ' td:eq(' + eq_td + ')').children().attr({
                    id: 'lbl_total_' + nuevo_cod,
                    data: nuevo_cod,
                });
                
                $(this).blur();
                $('#btn_agregar_compras').attr('disabled', 'disabled');
                $('.select_prod:last').focus();
            }
        }
    });

    $(document).on('keyup', '.txt_costo', function(){
        var cod = $(this).attr('data');

        var producto = $('#select_prod_' + cod).val();
        var costo = $('#txt_costo_' + cod).val();
        var cantidad = $('#txt_cantidad_' + cod).val();

        var total = cantidad * costo;
        $('#lbl_total_' + cod).html(dos_decimales(total));

        var row_count = $("#tbd_compras tr").length;
                
        var subtotal = 0;
        for(var i=1;i<=row_count;i++){
            var suma = Number($('#lbl_total_' + i).html());
            subtotal += suma;
        }
        $('#total_sub_total').html(dos_decimales(subtotal));
        subtotal = 0;

        var ver_igv = igv * $('#total_sub_total').html();
        $('#ver_igv').html(dos_decimales(ver_igv));

        var total_total = Number($('#ver_igv').html()) + Number($('#total_sub_total').html());
        $('#total_total').html(dos_decimales(total_total));
    }); 

    $(document).on('keyup', '.txt_cantidad', function(){
        var cod = $(this).attr('data');

        var producto = $('#select_prod_' + cod).val();
        var costo = $('#txt_costo_' + cod).val();
        var cantidad = $('#txt_cantidad_' + cod).val();

        var total = cantidad * costo;
        $('#lbl_total_' + cod).html(dos_decimales(total));

        var row_count = $("#tbd_compras tr").length;
                
        var subtotal = 0;
        for(var i=1;i<=row_count;i++){
            var suma = Number($('#lbl_total_' + i).html());
            subtotal += suma;
        }
        $('#total_sub_total').html(dos_decimales(subtotal));
        subtotal = 0;

        var ver_igv = igv * $('#total_sub_total').html();
        $('#ver_igv').html(dos_decimales(ver_igv));

        var total_total = Number($('#ver_igv').html()) + Number($('#total_sub_total').html());
        $('#total_total').html(dos_decimales(total_total));
    });    

    $(document).on('submit', '#form_compras', function(e){
        e.preventDefault();
    })

    $(document).on('click', '#btn_registrar_compra', function(){
        apprise('¿Está seguro que desea registrar la compra?', {'animate': true, 'verify': true}, function(answer){
            if($('#hd_proveedor_seleccionado').val() == ''){
                apprise('Seleccione un proveedor.');
            } else if($('#txt_factura').val() == ''){
                apprise('Escribe el N° de la factura.');
            } else {
                if(answer){
                    $.post($('#form_compras').attr('action'), $('#form_compras').serialize(), function(response){
                        apprise(response, {'animate': true});
                        $('#div_compras').load(url_actual + ' #div_compras_act');
                    }).fail(function(response){
                        apprise('Error inesperado, intente nuevamente.')
                        console.log(response);
                    }); 
                }
            }            
        }); 
    });

    $(document).on('click', '#spn_proveedor_seleccionado', function(){
        $('#hd_proveedor_seleccionado').val('');
        $('#spn_proveedor_seleccionado').hide();
        $('#btn_seleccionar_proveedor').show();
    });

    $(document).on('click', '#actualizar_vista', function(){
        $('#div_compras').load(url_actual + ' #div_compras_act');
    });

    $(document).on('submit', '#form_reg_prov', function(){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response);
            $('#registrar_proveedor').modal('hide');
            $('#form_reg_prov')[0].reset();
            $('#div_proveedores').load(url_actual + ' #div_proveedores_act');
            setTimeout(function(){
                data_table_proveedores();
            }, 1500);
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

});

function seleccionar_proveedor(proveedor){
    $('#hd_proveedor_seleccionado').val(proveedor);
    $('#seleccionar_proveedor').modal('hide');
    $('#spn_proveedor_seleccionado').show();
    $('#btn_seleccionar_proveedor').hide();
}

function dos_decimales(numero){
    var flotante = parseFloat(numero);
    var resultado = Math.round(flotante * 100) / 100;
    return resultado;
}