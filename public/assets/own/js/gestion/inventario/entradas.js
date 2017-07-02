var urlInventario = $('#ur').attr('name');
var url           = $('#u').attr('name');

urlActual  = $('#url_actual').val();
urlGeneral = $('#generar_url').val();

//Botón agregar entradas
btn_agregar = '#agregar_entradas';
//Botón quitar entradas
btn_quitar = '#quitar_entradas';

$(function(){
  
  disable(0, btn_agregar);
  disable(0, btn_quitar);

  $(document).on('click', '.chk_eliminar', function(){
    if($( "input:checkbox.chk_eliminar:checked").length > 0)
      disable(1, btn_quitar);
    else
      disable(0, btn_quitar);
  });

  $('#sel_prod_inv1').focus();
  $('#precio1').attr('readonly', true);
  $('#entrada1').attr('readonly', true);

  /****************Clonación de un tr*************************/
  tr          = $("#dataTable tbody#entradas tr.datos_entradas:eq(0)").clone();
  tbody       = "#dataTable tbody#entradas";
  tr_alterar  = "#dataTable tbody#entradas tr.datos_entradas";

  $(document).on('click', btn_agregar,function(){
    clonarFila(tr, tbody, tr_alterar);
    disable(0, this);
    Metis.formGeneral();
  });

  $(document).on('click', btn_quitar, function(){

    if($("input:checkbox.chk_eliminar:checked").length == 0)
      apprise('Seleccione al menos una fila');
    else{
      chk_marcados = $( "input:checkbox.chk_eliminar:checked" );

      $.each(chk_marcados, function(i, e){
        $(e).parent().parent().remove();
      });
      ultimo_tr_remover = $(tr_alterar+":last").children().children().attr('data-id');
      //Reseteamos la suma
      suma_exist_prec(ultimo_tr_remover);
      disable(1, '#agregar_entradas');
    }
  });

  /*************Selección de un producto********************/
  $(document).on('change', '.slc_producto', function(){

    id = $(this).val();
    data_id = $(this).attr('data-id');

    suma_total_prec_tot = 0;

    num_elem = Number($('tr.datos_entradas td label.lbl_verPrecTot').length) - 1;

    $('tr.datos_entradas td label.lbl_verPrecTot').each(function(index, elemento){

      if(index == num_elem){
        suma_total_prec_tot = suma_total_prec_tot;
        return false;
      }else{
        suma_total_prec_tot = Number(suma_total_prec_tot) + Number($(elemento).text());
      }

    });

    disable(id, btn_agregar);

    completarFila(id, data_id, suma_total_prec_tot);

  });

  /*******Al escribir en el campo de precio o entrada********/
  $(document).on('keyup', '.inventario', function(){

    input_orden = $(this).attr('data-id');
    suma_exist_prec(input_orden);

  });

  $(document).on('submit', '#form_registro_entradas', function(event){

      if($(tbody).find('tr').length == 0){
        apprise('Debe Agregar al menos una fila');
      }else{
        var error = validarCampos();

        if(error > 0){
          event.preventDefault();
          //$('#mensaje_error').html('Error: <br>* Completar campos requeridos');
        }else{
          apprise('¿Seguro que desea enviar?', {'verify':true, 'animate':true}, function(r){
              if(r){
                  $.ajax({
                    url: $('#form_registro_entradas').attr('action'),
                    type: $('#form_registro_entradas').attr('method'),
                    data: $('#form_registro_entradas').serialize(),
                    beforeSend:function(){     
                      //$('#preload').html('<img src="'+url+'/assets/products_img/load.GIF">');
                      $('#preload').html('<div class="progress mini progress-striped active">'
                                            + '<div class="progress-bar" style="width: 95%;">'
                                            + '</div>'
                                        +'</div>');
                    }
                  }).done(function(data){
                    $('#preload').html('');
                    if(data == 'error_registro'){
                      $('#mensaje_error').html('Error; No se pudo realizar el envío:' 
                                                          + '<br>* Verifique haber completado todos los campos correctamente'
                                                          + '<br>* Campo precio debe ser numérico y mayor a 0'
                                                          + '<br>* Campo entrada solo debe ser número entero y mayor a 0');
                      return false;
                    }
                      $('#div_entradas').load($('#url_actual').val()+' #load');
                      setTimeout(function(){
                        Metis.formGeneral();  
                      }, 2000);
                  }).fail(function(data){
                    apprise('Error inesperado, intente nuevamente.');
                    $('#preload').html('');
                  });
              }
          });
        }
      }
        event.preventDefault();
    });

});

/********Función para desabilitar el boton agregar***********/
function disable(val, elemento){
  if(val == 0)
    $(elemento).attr('disabled', true);
  else
    $(elemento).attr('disabled', false);
}

/******Función para clonar una fila, y alterar sus elementoss******/
function clonarFila(tr_clonar, tbody_agregar, tr_alterar){

  $(tr_clonar).clone().appendTo(tbody_agregar);
  
  var eq_tr = $(tr_alterar+":last").index();
  var eq_id = 1+Number($('#increment_id').attr('data-id'));
  $('#increment_id').attr('data-id', eq_id)

  $(tr_alterar+":eq("+eq_tr+") td").each(function(i, e){

    switch(i){

      case 0: $(e).children().attr({
                id       : 'chk_elim'+eq_id,
                name     : 'chk_elim'+eq_id, 
                'data-id': eq_id,
                value    : eq_id
              });
              break;

      case 1: $(e).children().attr({
                id       : 'sel_prod_inv'+eq_id,
                'data-id': eq_id
              });
              break;

      case 2: $(e).children().attr({
                id       : 'precio'+eq_id,
                'data-id': eq_id
              });       
              break;

      case 3: $(e).children().attr({
                id       : 'entrada'+eq_id,
                'data-id': eq_id
              });       
              break;

      case 4: $(e).children().attr({
                id       : 'lbl_Existencia'+eq_id,
                name     : "lbl_Existencia"+eq_id,
                'data-id': eq_id
              });
              $(e).children().next().attr({
                id       : 'lbl_verExistencia'+eq_id,
                name     : "lbl_verExistencia"+eq_id,
                'data-id': eq_id
              });
              break;

      case 5: $(e).children().attr({
                id       : 'lbl_PrecTot'+eq_id,
                name     : "lbl_PrecTot"+eq_id,
                'data-id': eq_id
              });
              $(e).children().next().attr({
                id       : 'lbl_verPrecTot'+eq_id,
                name     : "lbl_verPrecTot"+eq_id,
                'data-id': eq_id
              });       
              break;
      
      default:console.log('Error');
              break;
    }

  });

}

/*************Función Ajax para listar los campos de inventario***************/
function listaInventario(id, data_id, suma_total_prec_tot){

  $.ajax({
    url: urlInventario+'/filtrarinventario/'+id,
    type: 'post',
    dataType: 'json',
    success:function(data){
      if(data.idProducto == 0){
        $('#precio'+data_id).val('');
        $('#entrada'+data_id).val('');
        $('#lbl_Existencia'+data_id).html('0');
        $('#lbl_verExistencia'+data_id).html('0');
        $('#lbl_PrecTot'+data_id).html('0');
        $('#lbl_verPrecTot'+data_id).html('0');
        $('output#total_inv').html(suma_total_prec_tot);
      }       
      else{
        if(data.Costo == '0'){
          $('#precio'+data_id).val('');
          $('#precio'+data_id).focus();
        }else{
          $('#precio'+data_id).val(Number(data.Costo).toFixed(2));
          $('#entrada'+data_id).focus();
        }
        $('#entrada'+data_id).val('');
        $('#lbl_Existencia'+data_id).html(data.Existencia);
        $('#lbl_verExistencia'+data_id).html(data.Existencia);
        $('#lbl_PrecTot'+data_id).html(Number(data.Existencia*data.Costo).toFixed(2));
        $('#lbl_verPrecTot'+data_id).html(Number(data.Existencia*data.Costo).toFixed(2));
        $('output#total_inv').html(Number(suma_total_prec_tot+Number(data.Existencia*data.Costo)).toFixed(2));
      }
        
      //apprise(data.idProducto);
    },  
    error:function(){
      apprise('Error');
    }
  });

}

/******Completamos la fila de acuerdo al producto que se selecciona*********/
function completarFila(id, data_id, suma_total_prec_tot){

  if(id == '0'){
    //Desactivamos las cajas
    $('#precio'+data_id).attr('readonly', true);
    $('#entrada'+data_id).attr('readonly', true);
    //completamos los demas campos
    $('#precio'+data_id).val('');
    $('#entrada'+data_id).val('');
    $('#lbl_Existencia'+data_id).html('0');
    $('#lbl_verExistencia'+data_id).html('0');
    $('#lbl_PrecTot'+data_id).html('0');
    $('#lbl_verPrecTot'+data_id).html('0');
    $('output#total_inv').html(suma_total_prec_tot);
  }else{
    //Activamos las cajas de texto
    $('#precio'+data_id).attr('readonly', false);
    $('#entrada'+data_id).attr('readonly', false);
    listaInventario(id, data_id, suma_total_prec_tot);
  }

}

function suma_exist_prec(input_orden){

  precio_orden = ($('#precio'+input_orden).val() == '')?'0':$('#precio'+input_orden).val();
  entrad_orden = ($('#entrada'+input_orden).val() == '')?'0':$('#entrada'+input_orden).val();
  cant_exist_orden = $('#lbl_Existencia'+input_orden).html();
  prectot_orden_act = $('#lbl_PrecTot'+input_orden).html();

  sum_exist_nueva = Number(entrad_orden) + Number(cant_exist_orden);
  prec_tot_nuevo = roundToTwo(Number(sum_exist_nueva)*parseFloat(precio_orden));

  $('#lbl_verExistencia'+input_orden).html(sum_exist_nueva);
  $('#lbl_verPrecTot'+input_orden).html(prec_tot_nuevo.toFixed(2));

  suma_total_prec_tot = 0;

  $('tr.datos_entradas td label.lbl_verPrecTot').each(function(index, elemento){

      suma_total_prec_tot = Number(suma_total_prec_tot) + Number($(elemento).text());        
      //console.log('El elemento '+index+' contiene este texto '+$(elemento).text());
    });

  $('output#total_inv').html(suma_total_prec_tot.toFixed(2));

}

//Validamos los campos que no esten completos o con datos incorrectos
function validarCampos(){

  error = 0;

  $('.inventario').each(function(i, e){

    if($(e).val() == ''){
      $(e).css({'border' : '1px solid red'});
      error++;
    }else{
      $(e).css({'border' : '1px solid #ccc'});
    }
  });

  $('.precio').each(function(i, e){

    if(NumeroDecimal($(e).val()) == false){
      $(e).css({'border' : '1px solid red'});
      error++;
    }else{
      $(e).css({'border' : '1px solid #ccc'});
    }
  });

  $('.entrada').each(function(i, e){

    if(NumeroEntero($(e).val()) == false){
      $(e).css({'border' : '1px solid red'});
      error++;
    }else{
      $(e).css({'border' : '1px solid #ccc'});
    }
  });

  return error;

}

//Redondear a 2 decimales
function roundToTwo(num) {    
      return Number(Math.round(num + "e+2")  + "e-2");
  }

//Comparar si un numero es decimal
function NumeroDecimal(number) {
    if (number < 0.1 || number > 999999.99 || parseInt(100*number) != 100*number)
        return false;

    return true;
}

//Comparar si un numero es entero
function NumeroEntero(number){
  if(number < 1 || number > 99999999 || number != parseInt(number))
    return false;

  return true;
}