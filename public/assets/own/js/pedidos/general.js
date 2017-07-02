$(function(){

    $('.cantidad').keydown(function(e){
        if(e.which === 13){
            var index = $('.cantidad').index(this) + 1;
            $('.cantidad').eq(index).focus();
        }
    });

    $('#btn_realizar_pedidos').click(function(){
        apprise('¿Está seguro de realizar el pedido?', {'verify':true, 'animate':true}, function(r){
            if(r){
                $('#form_ped_prod').submit();
            }
        });
    });

    $('#btn_modificar_pedidos').click(function(){
        apprise('¿Está seguro de modificar el pedido?', {'verify':true, 'animate':true}, function(r){
            if(r){
                $('#form_mod_ped').submit();
            }
        });
    });

});

var total_final;

function prec_cantidad(id, todprod){
    var cant = $('#num_' + id).val();                    
    var prec = $('#lbl_' + id).val();
    var total = cant * prec;
    $('#lbl_total_parcial_' + id).val(total);
    sumatotal(todprod);
}

function sumatotal(filas){
    var suma = 0;
    for(var i=1;i<=filas;i++){
        var subtotal = Number($("#lbl_total_parcial_"+ i).val());
        suma = suma + subtotal;
    }
    $('#lbl_total_total').val(suma);
    suma = 0;
}