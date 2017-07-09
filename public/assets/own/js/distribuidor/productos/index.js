var urlProductos = $('#ur').attr('name');
var url = $('#u').attr('name');

// Bandera definida para porder volver a reutilizarlo cuando invoque la funcion codbarrasExist(); sin enviarle parametros
var bandera_codBarras = false;
var bandera_nomProducto = false;
$(function(){
    data_table_ProductosDist('#data_table_productos');
    $('#img_file').bootstrapFileInput();
    $('#camb_img_file').bootstrapFileInput();

    $(document).on('click', '.icono_prod', function(){
        $('#prod_det .modal-title').html($(this).attr('alt'));
        $('#imagen_producto').html('<img src="'+ $(this).attr('src') +'" style="width: 350px; height: 350px" id="img_prod_cambiar">');
        $('#url_img_prod').attr('url', $(this).attr('src'));
        $('#detalle_producto').html($(this).attr('name'));
        $('#prod_det #inp').html('<input type="hidden" value="'+ $(this).attr('data-id') +'" name="id_prod_dist_ult">');
    });

    $('#btn_cambiar_img_prod').attr('disabled', true);

    $(document).on('change', '#camb_img_file', function(){
        if(comprobar_extension(this.files[0].name) == 0){
            $(this).val('');
            $('#btn_cambiar_img_prod').attr('disabled', true);
            $('#img_prod_cambiar').attr('src', $('#url_img_prod').attr('url'));
            return false;
        }
        if(comprobar_tamanho(this.files[0].size/1024/1024) == 0){
            $(this).val('');
            $('#btn_cambiar_img_prod').attr('disabled', true);
            $('#img_prod_cambiar').attr('src', $('#url_img_prod').attr('url'));
            return false;
        }
        $('#btn_cambiar_img_prod').attr('disabled', false);
        readImage(this);
    });

    $("#msg_alert2").hide();
    $("#div_progress2").hide();
    $('#btn_cambiar_img_prod').click(function(){
        if($('#camb_img_file').val() == ''){
            $('#btn_cambiar_img_prod').attr('disabled', true);
            $('#img_prod_cambiar').attr('src', $('#url_img_prod').attr('url'));
            apprise('Seleccione una imagen');
            return false;
        }
        $('#form_cambiar_img_prod').ajaxForm({
            beforeSend: function(){
                $("#div_progress2").show();
            },
            uploadProgress: function(event, position, total, percentComplete){
                $(".progress-bar-success").width(percentComplete + '%');
                $(".progress-bar-success").attr('aria-valuenow', percentComplete);
                $(".sr-only").html(percentComplete + '%');
            },
            success: function(){
                $("#div_progress2").hide();
            },
            complete: function(response){
                var mensaje = '';
                if(response.responseText == '{"success":true}'){
                    $("#msg_alert2").removeClass('bg-red lter');
                    $("#msg_alert2").addClass('bg-blue');
                    mensaje = 'Éxito al subir archivo';
                    $('#tabla-productos').load(urlProductos + ' #load-productos');
                    setTimeout(function(){
                        data_table_ProductosDist('#data_table_productos');
                    }, 3000);
                } else {
                    $("#msg_alert2").removeClass('bg-blue');
                    $("#msg_alert2").addClass('bg-red lter');
                    mensaje = 'Error al subir archivo';
                }
                $("#msg_alert2").show();
                $("#msg_alert2").html(mensaje);
                $(".progress-bar-success").width(0 + '%');
                $(".progress-bar-success").attr('aria-valuenow', 0);
                $(".sr-only").html(0+'%');
                $("#msg_alert2").fadeOut(2500, 'linear');
            }
        });
    });

    $('#a_nuevo_producto').click(function(){
        limpiarForm('#frm_productos_dist');
        establecerFormInicial($('#frm_productos_dist'));
    });

    $('#img_file').change(function(){
        comprobar_extension(this.files[0].name);
        comprobar_tamanho(this.files[0].size/1024/1024);
    });

    $('#txtcodbarras').change(function(){
        var codbarras = $(this).val();
        var arreglo_codBarras = listaproductos()[0].CodBarrasDist;
        $.each(arreglo_codBarras, function(index, element){
            if(codbarras == element){
                $('#txtcodbarras').css({'border' : '1px solid red'});
                $('#error_codbarras').text('Código Barras ya existe');
                $('#error_codbarras').show();
                bandera_codBarras = true;
                return false;
            } else {
                $('#txtcodbarras').css({'border' : '1px solid #ccc'});
                $('#error_codbarras').text('');
                $('#error_codbarras').hide();
                bandera_codBarras = false;
            }
        });
        codbarrasExist(bandera_codBarras)
    });

    $('#txtnomProducto').change(function(){
        var nomProducto = $(this).val();
        var arreglo_nomProducto = listaproductos()[1].NombreProductoDist;
        $.each(arreglo_nomProducto, function(index, element){
            if(nomProducto == element){
                $('#txtnomProducto').css({border: '1px solid red'});
                $('#error_nomProducto').text('Nombre ya existe');
                $('#error_nomProducto').show();
                bandera_nomProducto = true;
                return false;
            } else {
                $('#txtnomProducto').css({border: '1px solid #ccc'});
                $('#error_nomProducto').text('');
                $('#error_nomProducto').hide();
                bandera_nomProducto = false;
            }
        });
        nomProductoExist(bandera_nomProducto);
    });

    $('#frm_productos_dist').submit(function(event){
        event.preventDefault();
    });

    $('#btn_RegProdDist').click(function(){
        var btn = $(this);
        var error_validacion = validaForm($('#frm_productos_dist'));
        if(error_validacion == 0 && nomProductoExist() == false){
            apprise('¿Seguro que desea enviar formulario?', {'animate': true, 'verify': true}, function(answer){
                if(answer){
                    registrarProductoDist(btn);
                } else {
                    return false;
                }
            });
        }
    });

    $('.cls-req').change(function(){
        limpiarValidaForm(this);
    });

    $("#msg_alert").hide();
    $("#div_progress").hide();
    $('#btn_UploadProdDist').click(function(){
        $('#frm_productos_dist_upload').ajaxForm({
            beforeSend: function(){
                $("#div_progress").show();
            },
            uploadProgress: function(event, position, total, percentComplete){
                $(".progress-bar-success").width(percentComplete + '%');
                $(".progress-bar-success").attr('aria-valuenow', percentComplete);
                $(".sr-only").html(percentComplete + '%');
            },
            success:function(){
                $("#div_progress").hide();
            },
            complete:function(response){
                var mensaje = '';
                if(response.responseText == '{"success":true}'){
                    $("#msg_alert").removeClass('bg-red lter');
                    $("#msg_alert").addClass('bg-blue');
                    mensaje = 'Éxito al subir archivo';
                    $('#tabla-productos').load(urlProductos + ' #load-productos');
                    setTimeout(function(){
                        data_table_ProductosDist('#data_table_productos');
                    }, 3000);
                } else {
                    $("#msg_alert").removeClass('bg-blue');
                    $("#msg_alert").addClass('bg-red lter');
                    mensaje = 'Error al subir archivo';
                }
                $("#msg_alert").show();
                $("#msg_alert").html(mensaje);
                $(".progress-bar-success").width(0 + '%');
                $(".progress-bar-success").attr('aria-valuenow', 0);
                $(".sr-only").html(0 + '%');
                $("#msg_alert").fadeOut(2500, 'linear');
            }
        });
    });

    $(document).on('click', '.eliminar', function(){
		var id = $(this).attr('data-id');
		var row = $(this);
		apprise('¿Seguro que desea eliminar?', {animate: true, verify: true}, function(answer){
		    if(answer){
		        eliminarProducto(id, row);
		    } else {
		        return false;
		    }
		});
    });

});

function codbarrasExist(prm_bandera){
    var bandera_ret = prm_bandera || bandera_codBarras;
    return bandera_ret;
}

function nomProductoExist(prm_bandera){
    var bandera_ret = prm_bandera || bandera_nomProducto;
    return bandera_ret;
}

function validaForm(miForm){
    var errors = 0;

    $(':input', miForm).each(function(){
        var type = this.type;
        var tag = this.tagName.toLowerCase();

        if(type == 'text' || type == 'file'){
            if($(this).val() == ''){
                $(this).css({'border' : '1px solid red'});
                $(this).next().text('* Campo obligatorio');
                $(this).next().show();
                /** ----(I)
                 * Debido a la plantilla que se tiene realizamos la siguiente línea de código en otros casos no es necesario
                 */
                if(type == 'file'){
                    $(this).parent().css({'border' : '1px solid red'});
                    $(this).parent().next().html('<b>* Campo obligatorio</b>');
                    $(this).parent().next().show();
                }
                /** ----(F)*/
                errors++;
			} else {
                $(this).css({border: '1px solid #ccc'});
                $(this).next().text('');
                $(this).next().hide();
                /** ----(I)
                 * Debido a la plantilla que se tiene realizamos la siguiente línea de código en otros casos no es necesario
                 */
                if(type == 'file'){
                    $(this).parent().css({'border' : '1px solid #ccc'});
                    $(this).parent().next().html('');
                    $(this).parent().next().hide();
                }
                /** ----(F)*/
            }
        } else if(tag == 'select'){
            if($(this).val() == '0'){
				$(this).css({border: '1px solid red'});
				$(this).next().text('* Seleccione categoria');
				$(this).next().show();
				errors++;
            } else {
                $(this).css({border: '1px solid #ccc'});
                $(this).next().text('');
                $(this).next().hide();
            }
        }
    });
	return errors;
}

function limpiarForm(idForm){
    $(idForm)[0].reset();
}

function limpiarValidaForm(cls_req){
    var type = cls_req.type;
    var tag = cls_req.tagName.toLowerCase();

    if(type == 'text' || type == 'file'){
        if($(cls_req).val() != ''){
            $(cls_req).css({'border' : '1px solid #ccc'});
            $(cls_req).next().text('');
            $(cls_req).next().hide();
            /** ----(I)
             * Debido a la plantilla que se tiene realizamos la siguiente línea de código en otros casos no es necesario
             */
            if(type == 'file'){
                $(cls_req).parent().css({border: '1px solid #ccc'});
                $(cls_req).parent().next().html('');
                $(cls_req).parent().next().hide();
            }
            /** ----(F)*/
        }
    } else if(tag == 'select'){
        if($(cls_req).val() != '0'){
            $(cls_req).css({border: '1px solid #ccc'});
            $(cls_req).next().text('');
            $(cls_req).next().hide();
        }
    }
}

function establecerFormInicial(miForm){
    $(':input', miForm).each(function(){
        var type = this.type;
        var tag = this.tagName.toLowerCase();

        if(type == 'text' || tag == 'select' ){
            $(this).css({'border' : '1px solid #ccc'});
            $(this).next().text('');
            $(this).next().hide();
        } else if(type == 'file'){
            $(this).parent().css({'border' : '1px solid #ccc'});
            $(this).parent().next().html('');
            $(this).parent().next().hide();
        }
    });
}

function registrarProductoDist(btn){
    $.ajax({
        url: $('#frm_productos_dist').attr('action'),
		method: $('#frm_productos_dist').attr('method'),
        data: $('#frm_productos_dist').serialize(),
        beforeSend: function(){
            btn.hide();
            btn.after('<img src="' + url + '/assets/img/load2.GIF">');
        }
    }).done(function(data){
        if(data.errors == 1){
            $('#nuevo_producto').modal('hide');
            apprise(data.mensaje + ' ¿Desea agregar Imagen?', {verify: true}, function(r){
                if(r){
                    $('#UploadImgProducto').modal('show');
                    $('#id_prod_dist_ult').val(data.id);
                }
                $('#tabla-productos').load(urlProductos + ' #load-productos');
                setTimeout(function(){
                    data_table_ProductosDist('#data_table_productos');
                }, 3000);
                limpiarForm('#frm_productos_dist');
            });
        } else {
            apprise(data.mensaje);
        }
        btn.show();
        btn.next().remove();
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente.');
        btn.show();
        btn.next().remove();
    });
}

function eliminarProducto(id, row){
    $.ajax({
        url: urlProductos + '/eliminar-producto/' + id,
        type: 'post',
        beforeSend: function(){
            row.prev().hide();
            row.hide();
            row.after('<img src="'+url+'/assets/img/load2.GIF">');
        }
    }).done(function(data){
        if(data.success){
            row.next().remove();
            row.after('Eliminado <i class="glyphicon glyphicon-ok"></i>');
            setTimeout(function(){
                row.parent().parent().fadeOut(700, 'swing');
            }, 1000);
            setTimeout(function(){
                $('#tabla-productos').load(urlProductos + ' #load-productos');
            }, 2000);
            setTimeout(function(){
                data_table_ProductosDist('#data_table_productos');
            }, 5000);
        } else {
            apprise("Error al eliminar, intente de nuevo");
            row.prev().show();
            row.show();
            row.next().remove();
        }
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente.');
        row.prev().show();
        row.show();
        row.next().remove();
    });
}

function comprobar_extension(archivo){
    var extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".jpeg");
    var mierror = null;
    if(archivo){
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        var permitida = false;
        for(var i=0;i<extensiones_permitidas.length;i++){
            if(extensiones_permitidas[i] == extension){
                permitida = true;
                break;
            }
        }
        if(!permitida){
            mierror = "Comprueba la extensión de los archivos a subir. Sólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            $('#img_file').val('');
        } else {
            return 1;
        }
    }
    if(mierror != null){
        apprise(mierror);
        return 0;
    }
}

function comprobar_tamanho(tamanho){
    var tamReal = roundToTwo(tamanho);
    var tamIndi = Number(3.10);
    var error = null;
    var permitida = false;
    if(tamReal <= tamIndi){
        permitida = true;
    }
    if(!permitida){
        error = "El tamaño máximo es de 3 MB. Debe seleccionar otra imagen";
        $('#img_file').val('');
    } else {
        return 1;
    }
    if(error != null){
        apprise(error);
        return 0;
    }
}

function roundToTwo(num){
    return Number(Math.round(num + "e+2")  + "e-2");
}

function readImage(input){
    if(input.files && input.files[0]){
        var FR = new FileReader();
        FR.onload = function(e){
            $('#img_prod_cambiar').attr("src", e.target.result);
            $('#base').text(e.target.result);
        };
        FR.readAsDataURL(input.files[0]);
    }
}

function data_table_ProductosDist(idTabla){
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
