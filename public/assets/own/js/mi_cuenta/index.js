$(function(){
    $('#data_table_comisiones').dataTable();
});  

function eliminar_comision(comision){
    $.ajax({
        url: $('#url_eliminar_comision').val(),
        type: 'POST',
        data: 'comision=' + comision,
        dataType: 'html',
        success: function(response){
            apprise(response);
            $('#tb_com').load($('#url_actual').val() + ' #tb_com_act');
            setTimeout(function(){
                $('#data_table_comisiones').dataTable();
            }, 1000);
        }
    });
}