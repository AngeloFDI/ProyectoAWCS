$(document).ready(function(){
    $('form').submit(function(e){
        let ok = true;
        let msj = '';
        let correo = $('input[name="correo"]').val().trim();
        let pass = $('input[name="contrasena"]').val();
        let pass2 = $('#confirm-password').val();
        let nombre = $('input[name="nombre"]').val().trim();
        let apellido = $('input[name="apellido"]').val().trim();

        if (!nombre || !apellido || !correo || !pass || !pass2) {
            msj = "Por favor, complete todos los campos obligatorios.";
            ok = false;
        }
        else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo)) {
            msj = "Ingrese un correo válido.";
            ok = false;
        }
        else if (pass.length < 6) {
            msj = "La contraseña debe tener al menos 6 caracteres.";
            ok = false;
        }
        else if (pass !== pass2) {
            msj = "Las contraseñas no coinciden.";
            ok = false;
        }

        if (!ok) {
            $('#error-msg').html(msj).css('color','red');
            e.preventDefault();
        }
    });
});