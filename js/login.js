$(document).ready(function(){
    $('#login-form').submit(function(e){
        e.preventDefault();
        $('#login-alert').html('');
        var datos = {
            correo: $('input[name="correo"]').val(),
            contrasena: $('input[name="contrasena"]').val()
        };
        $.post('index.php?controller=auth&action=login_ajax', datos, function(resp){
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                if(r.success) {
                    // Redirecciona según el rol
                    if(r.rol === 'personal') {
                        window.location = 'index.php?controller=home&action=index&tipo=personal';
                    } else {
                        window.location = 'index.php?controller=home&action=index&tipo=estudiante';
                    }
                } else {
                    $('#login-alert').html('<div style="color:red;">'+r.msg+'</div>');
                }
            } catch(err) {
                $('#login-alert').html('<div style="color:red;">Respuesta inesperada del servidor.</div>');
            }
        });
    });
});