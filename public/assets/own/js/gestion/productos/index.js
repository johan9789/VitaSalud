$(function(){
    var urlProductos = $('#ur').attr('name');
    var url = $('#u').attr('name');

    $('#data_table_productos').dataTable();

    $('#img_file').bootstrapFileInput();
    
    $('#camb_img_file').bootstrapFileInput();
    
    $('#img_file').change(function(){
        //alert(this.value + " " + $(this).val() + "  " + this.files[0].name + ' tamaño: ' + roundToTwo(this.files[0].size/1024/1024) + ' MB');
        comprobar_extension(this.files[0].name);
        comprobar_tamanho(this.files[0].size/1024/1024);
    });

    function comprobar_extension(archivo){ 
        extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".jpeg");
        mierror = null; 
        if(archivo){
            extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
            permitida = false; 
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
            alert(mierror);
            return 0; 
        }
    }

    function comprobar_tamanho(tamanho){
        tamReal = roundToTwo(tamanho);
        tamIndi = Number(3.10);
        error = null;
        permitida = false;
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
        //alert($(this).attr('src'));
        /*var action = $('#form_cambiar_img_prod').attr('action');
        $('#form_cambiar_img_prod').attr('action', action+'/'+);*/
        $('#prod_det .modal-title').html($(this).attr('alt'));
        $('#imagen_producto').html('<img src="'+ $(this).attr('src') +'" style="width: 350px; height: 350px" id="img_prod_cambiar">');
        $('#url_img_prod').attr('url', $(this).attr('src'));
        $('#detalle_producto').html($(this).attr('name'));
        $('#prod_det #inp').html('<input type="hidden" value="'+ $(this).attr('data-id') +'" name="idProd">');
    });

    $(document).on('click', '.eliminar', function(){
        // alert($(this).attr('data-id'));
        var idp = $(this).attr('data-id');
        apprise('¿Está seguro de eliminar este producto?', {'animate':true, 'verify':true}, function(answer){
            if(answer){
                $.ajax({
                    url: urlProductos + "/eliminarprod/" + idp,
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
    });

    $(document).on('click', '.editar', function(){
        var idp = $(this).attr('data-id');
        $.post(urlProductos+"/editarprod/" + idp, function(data){
        	$('div#editar_producto #form_editar #codbarras').val(data.CodBarras);
            $('div#editar_producto #form_editar #producto').val(data.NombreProducto);
            $('div#editar_producto #form_editar #detalles').val(data.DetallesProducto);
            $('div#editar_producto #form_editar #categoria').val(data.idCategoriaProducto);
        }, 'json').fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        })
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
        })
        return false;
    });


    // Filtrar datos por categoria
    $('#sel_categoria').change(function(){
        //alert($(this).val());
        Filtrarcategoria($(this).val());
    });

    function Filtrarcategoria(idCat){
        $.ajax({
            url: urlProductos+"/filtrarprodxcat/"+idCat,
            type: 'post',
            dataType: 'json',
            success:function(data){
                //alert(data[0].NombreProducto);
                if(data.length == 0){
                    $('table tbody#productos').html('No existen datos');
                    return false;
                }
                $('table tbody#productos').html('');
                var productos = '';
                for(i in data){
                    productos += '<tr>';
                    productos += '<td>'+(Number(i)+1)+'</td>';
                    productos += '<td>';
                    productos += '<a data-toggle="modal" href="#prod_det">';
                    if(data[i].UrlFotoProducto == ''){
                        productos += '<img src="'+url+'/assets/products_img/product-default.png'+'" class="icono_prod" name="'+data[i].DetallesProducto+'" width="20" height="20" alt="producto" >';
                    } else {
                        productos += '<img src="'+url+'/assets/products_img/'+data[i].UrlFotoProducto+'" class="icono_prod" name="'+data[i].DetallesProducto+'" width="20" height="20" alt="producto">';
                    }
                    productos += '</a>';
                    productos += '</td>';
                    productos += '<td>'+data[i].NombreProducto+'</td>';
                    productos += '<td>S/. '+data[i].PrecioDistribuidor+'</td>';
                    productos += '<td>S/. '+data[i].PrecioPublico+'</td>';
                    productos += '<td>'+data[i].NombreCategoriaProducto+'</td>';
                    productos += '<td>';
                    productos += '<span class="editar" data-id="1">';
                    productos += '<a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#editar_producto">Editar';
                    productos += '</a>';
                    productos += '</span>';
                    productos += '</td>';
                    productos += '<td><button class="eliminar" data-id="'+ data[i].idProducto +'">Eliminar</button></td>';
                    productos += '</tr>';
                }
                $('table tbody#productos').html(productos);
            },
            error:function(){
                alert("Error");
            }
        });
    }

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
        //$('#imagen_producto').html('<img src="'+url+'/assets/products_img/load.GIF">');
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