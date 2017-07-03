var urlProductos = $('#ur').attr('name');
var url = $('#u').attr('name');

$(function(){
    $('#data_table_productos').dataTable();

    $('#img_file').bootstrapFileInput();
    
    $('#camb_img_file').bootstrapFileInput();
    
    $('#img_file').change(function(){
        comprobar_extension(this.files[0].name);
        comprobar_tamanho(this.files[0].size/1024/1024);
    });

    function comprobar_extension(archivo){ 
        var extensionesPermitidas = new Array(".gif", ".jpg", ".png", ".jpeg");
        var mierror = null;
        if(archivo){
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            var permitida = false;
            for(var i=0;i<extensionesPermitidas.length;i++){
                if(extensionesPermitidas[i] == extension){
                    permitida = true;
                    break;
                } 
            } 
            if(!permitida){ 
                mierror = "Comprueba la extensión de los archivos a subir. Sólo se pueden subir archivos con extensiones: " + extensionesPermitidas.join();
                $('#img_file').val(''); 
            } else { 
                return 1; 
            } 
        } 
        if(mierror != null){
            alert(mierror);
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
            alert(error);
            return 0;
        }
    }

    function roundToTwo(num){    
        return Number(Math.round(num + "e+2")  + "e-2");
    }

    $(document).on('click', '.icono_prod', function(){
        $('#prod_det .modal-title').html($(this).attr('alt'));
        $('#imagen_producto').html('<img src="'+ $(this).attr('src') +'" style="width: 350px; height: 350px" id="img_prod_cambiar">');
        $('#url_img_prod').attr('url', $(this).attr('src'));
        $('#detalle_producto').html($(this).attr('name'));
        $('#prod_det #inp').html('<input type="hidden" value="'+ $(this).attr('data-id') +'" name="idProd">');
    });

    $("#form_editar").submit(function(){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response);
            $('#collapse4').load($('#url_actual').val() + ' #data_table_productos');
            setTimeout(function(){
                $('#data_table_productos').dataTable();
            }, 1000);  
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        return false;
    });

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

    $('#btn_cambiar_img_prod').attr('disabled', true);

    $(document).on('click', '#btn_cambiar_img_prod', function(){
        if($('#camb_img_file').val() == ''){
            $('#btn_cambiar_img_prod').attr('disabled', true);
            $('#img_prod_cambiar').attr('src', $('#url_img_prod').attr('url'));
            alert('Seleccione una imagen');
            return false;
        }
        if(confirm("Seguro desea cambiar imagen")){
            $('#form_cambiar_img_prod').submit();
        } else {
            $('#img_prod_cambiar').attr('src', $('#url_img_prod').attr('url'));
            $('#camb_img_file').val('');
        }
    });

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

    $('#txtcodbarras').change(function(){
    	var codbarras = $(this).val();
    	var arregloproductos = listaproductos();
    	$.each(arregloproductos, function(index, element){
    		if(codbarras == element){
    			$('#txtcodbarras').css({'border' : '1px solid red'});
    			$('#error_codbarras').text('Código Barras ya existe');
    			$('#error_codbarras').show();
    			return false;
    		}else{
    			$('#txtcodbarras').css({'border' : '1px solid #ccc'});
    			$('#error_codbarras').text('');
    			$('#error_codbarras').hide();
    		}	
    		//console.log(codbarras+' '+element);
    	});
    	/*
    	 * LLamamos a la funcion establecida en el index
    	 * No se requiere importar o hacer un include como en php para acceder a otros datos	 
		var qs = listaproductos();
		 
		console.log(qs);*/
    });

});

function editarProducto(id){
    $.getJSON(urlProductos + "/editarprod/" + id, function(data){
        $('div#editar_producto #form_editar #codbarras').val(data.CodBarras);
        $('div#editar_producto #form_editar #producto').val(data.NombreProducto);
        $('div#editar_producto #form_editar #detalles').val(data.DetallesProducto);
        $('div#editar_producto #form_editar #categoria').val(data.idCategoriaProducto);
    }).fail(function(){
        apprise('Error inesperado, intente nuevamente.');
    });
}

function eliminarProducto(id){
    apprise('¿Está seguro de eliminar este producto?', {'animate':true, 'verify':true}, function(answer){
        if(answer){
            $.ajax({
                url: urlProductos + "/eliminarprod/" + id,
                type: 'post',
                beforeSend:function(){
                    $('#mensaje').html('<img src="'+url+'/assets/products_img/load.GIF">');
                }
            }).done(function(response){
                apprise(response);
                $('#collapse4').load($('#url_actual').val() + ' #data_table_productos');
                $('#mensaje').html('');
                setTimeout(function(){
                    $('#data_table_productos').dataTable();
                }, 1000);
            }).fail(function(){
                apprise('Error inesperado, intente nuevamente.');
            });
        } else {
            return false;
        }
    });
}
