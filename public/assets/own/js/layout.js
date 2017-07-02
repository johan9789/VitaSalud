$(function(){
    $('#file_XD').bootstrapFileInput();

    $('#form_editar_perfil').submit(function(e){		
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            apprise(response, {'animate':true});
            $('#edit_user_modal').modal('hide');
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        })
        e.preventDefault();
    });

    $('#form_cambiar_pass').submit(function(e){
        $.post($(this).attr('action'), $(this).serialize(), function(response){
            $('#form_cambiar_pass')[0].reset();
            apprise(response, {'animate':true});
            if(response == 'Se cambió la contraseña.'){
                $('#change_password_modal').modal('hide');
                $('#edit_user_modal').modal('hide');
            } else {
                $('#pass_act').focus();
            }
        }).fail(function(){
            apprise('Error inesperado, intente nuevamente.');
        });
        e.preventDefault();
    });

    $("#file_XD").change(function(){
        read_image(this);
    });

    $('#btn_cambiar_foto').click(function(){
        apprise('¿Está seguro de actualizar su foto de perfil?', {'verify':true, 'animate':true}, function(r){
            if(r){                             
                $('#form_cambiar_foto').submit();
            } else {
                $('#foto_cambiar').attr('src', $('#url_foto_XD').attr('url'));
                $('#file_XD').val('');
            }
        });
    });

});

function read_image(file){
    if(file.files && file.files[0]){
        var file_reader = new FileReader();
        file_reader.onload = function(e){
             $('#foto_cambiar').attr("src", e.target.result);
             $('#base').text(e.target.result);
        };       
        file_reader.readAsDataURL(file.files[0]);
    }
}