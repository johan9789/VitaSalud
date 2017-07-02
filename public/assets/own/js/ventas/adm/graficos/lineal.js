$(function(){

	$('#select_año').change(function(){
		top.location.href=$('#generar_url').val() + '/ventas/adm/graficos/lineal/' + $(this).val();
	});

});