$(document).ready(function () {
    $('#editar-perfil').submit(function (e) {
        e.preventDefault();
        var datos = $(this).serialize();
        $.post('index.php?controller=perfil&action=actualizar', datos, function (resp) {
            try {
                var r = (typeof resp === 'object') ? resp : JSON.parse(resp);
                if (r.success) {
                    // Alertas
                    Swal.fire({
                        icon: 'success',
                        title: 'Perfil actualizado',
                        text: r.msg,
                        showConfirmButton: false,
                        timer: 1400
                    }).then(() => {
                        window.location = 'index.php?controller=home&action=index'; //Redirige a home
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: r.msg
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error inesperado',
                    text: 'Error inesperado al actualizar'
                });
            }
        });
    });
});