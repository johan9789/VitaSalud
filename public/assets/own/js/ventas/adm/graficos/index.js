$(function(){
    Metis.formGeneral();
    var generar_url = $('#generar_url').val();

    $('#btn_int_fechas').click(function(){
        var fecha = $('#reservation').val();
        if(fecha == ''){
        	top.location.href=generar_url + '/ventas/adm/graficos';
        } else {
            var part_fecha = fecha.split(" - ");

            var part_fecha1 = part_fecha[0].split("/");
            var part_fecha2 = part_fecha[1].split("/");

            var fecha1 = part_fecha1[2] + '-' + part_fecha1[1] + '-' + part_fecha1[0];
            var fecha2 = part_fecha2[2] + '-' + part_fecha2[1] + '-' + part_fecha2[0];        

			top.location.href=generar_url + '/ventas/adm/graficos/fechas/' + fecha1 + '/' + fecha2;
        }
    });

});