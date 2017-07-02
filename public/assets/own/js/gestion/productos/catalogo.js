$(function(){

	var urlProductos = $('#ur').attr('name');
    var url = $('#u').attr('name');

    /*$('.col-lg-3').mouseover(
    	function(){
    		$(this).removeClass('col-lg-3');
    		$(this).addClass('active col-lg-3 success');
    	}).mouseout(
    	function(){
    		$(this).removeClass('active col-lg-3 success');
    		$(this).addClass('col-lg-3');
    	}
    );*/

    $(document).on('mouseover', '.col-lg-3', function(){
      $(this).removeClass('col-lg-3');
      $(this).addClass('active col-lg-3 success');
    });

    $(document).on('mouseout', '.col-lg-3', function(){
      $(this).removeClass('active col-lg-3 success');
      $(this).addClass('col-lg-3');
    });

    $('.rad-list-categ').change(function(){
        //alert($(this).val());
        Filtrarcategoria($(this).val());
    });


    function Filtrarcategoria(idCat){
      $.ajax({

            url: url+"/gestion/productos/filtrarprodxcat/"+idCat,
            type: 'post',
            dataType: 'json',
            beforeSend:function(){
                $('ul#light').html('<img src="'+url+'/assets/products_img/load.GIF">');
            },
            success:function(data){
                //alert(data[0].NombreProducto);

                if(data.length == 0){
                  $('ul#light').html('No existen datos');
                  return false;
                }

                $('ul#light').html('');
                var productos = '';
                for(i in data){
                  productos += '<li class="col-lg-3">';
                  productos += '<h3>'+data[i].NombreProducto+'</h3>';
                  productos += '<div class="price-body">';
                  productos += '<div class="price">';
                  if(data[i].UrlFotoProducto=='')
                    productos += '<img src="'+url+'/assets/products_img/product-default.png'+'" class="icono_prod" name="'+data[i].DetallesProducto+'" width="120" height="120" style="padding: 10px; border-radius: 100%" >';
                  else
                    productos += '<img src="'+url+'/assets/products_img/'+data[i].UrlFotoProducto+'" class="icono_prod" name="'+data[i].DetallesProducto+'" width="120" height="120" style="padding: 10px; border-radius: 100%" >';
                  productos += '</div>';
                  productos += '</div>';
                  productos += '<div class="features">';
                  productos += '<ul>';
                  productos += '<li><strong>S/. '+Number(data[i].PrecPublico).toFixed(2)+'</strong></li>';
                  if(data[i].DetallesProducto.length > 30)
                  productos += '<li>'+data[i].DetallesProducto.substr(0, 30)+'...</li>';
                  else
                  productos += '<li>'+data[i].DetallesProducto+'</li>';
                  productos += '</ul>';
                  productos += '</div>';
                  productos += '<div class="footer">';
                  productos += '<a data-toggle="modal" href="#verFicha" class="btn btn-info btn-rect" id="a-verFicha">Ver Ficha</a>';
                  productos += '</div>';
                  productos += '</li>';
                }
                productos += '<div class="clearfix"></div>';
                $('ul#light').html(productos);
                
            },
            error:function(){
                alert("Error");
            }

        });
    }

    $(document).on('click', '#a-verFicha', function(){
      //var NombreProducto = $(this).parent().prevAll('h3').html();
      var nombreProducto = $(this).parent().prev().prev().prev().html();
      var precioProducto = $(this).parent().prev().children().children().children().html();
      var srcImagen = $(this).parent().prev().prev().children().children().attr('src');
      var detalle = $(this).parent().prev().prev().children().children().attr('name');
      $('#verFicha h4.modal-title').html(nombreProducto + " " +precioProducto);
      $('#imagen_producto').html('<img src="'+ srcImagen +'" style="width: 350px; height: 350px">');
      $('#detalle_producto').html(detalle);
    });

});