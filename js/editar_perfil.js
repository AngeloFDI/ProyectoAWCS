$(document).ready(function () {
    $('#editar-perfil').submit(function (e) {
        e.preventDefault();
        $('#alerta-perfil').html('');
        var datos = $(this).serialize();
        $.post('index.php?controller=perfil&action=actualizar', datos, function (resp) {
            try {
                var r = (typeof resp === 'object') ? resp : JSON.parse(resp);
                if (r.success) {
                    $('#alerta-perfil').html('<div style="color:green;font-weight:bold">' + r.msg + '</div>');
                } else {
                    $('#alerta-perfil').html('<div style="color:red;">' + r.msg + '</div>');
                }
            } catch (err) {
                $('#alerta-perfil').html('<div style="color:red;">Error inesperado al actualizar</div>');
            }
        });
    });
});