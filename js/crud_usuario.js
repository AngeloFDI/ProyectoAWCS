$(document).ready(function () {

    // Validación y submit para crear/editar usuario
    if ($('#form-usuario').length) {
        $('#form-usuario').submit(function (e) {
            e.preventDefault();
            $('#alerta-usuario').html('');

            // VALIDACIONES previas (puedes ajustar a tu gusto)
            const nombre = $('input[name="nombre"]').val().trim();
            const apellido = $('input[name="apellido"]').val().trim();
            const correo = $('input[name="correo"]').val().trim();
            const rol = $('select[name="rol"]').val();
            const pass = $('input[name="contrasena"]').val();
            let modo = $(this).find('button[type="submit"]').html().toLowerCase().includes('registrar') ? 'crear' : 'editar';

            let errores = [];
            if (!nombre) errores.push('El nombre es obligatorio');
            if (!apellido) errores.push('El apellido es obligatorio');
            if (!correo) errores.push('El correo es obligatorio');
            if (modo === 'crear' && (!pass || pass.length < 6)) errores.push('La contraseña es obligatoria y debe tener al menos 6 caracteres');

            if (errores.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el formulario',
                    html: errores.join('<br>')
                });
                return;
            }

            var datos = $(this).serialize();
            var url = 'index.php?controller=personas&action=' + (modo == 'crear' ? 'crear' : 'editar');
            $.post(url, datos, function (resp) {
                try {
                    var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                    if (r.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: r.msg,
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => {
                            window.location = 'index.php?controller=personas&action=index';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: r.msg
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'Ocurrió un error inesperado'
                    });
                }
            });
        });
    }

    // Eliminar usuario (con confirmación SweetAlert)
    $(document).on('click', '.borrarUsuario', function () {
        var id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: '¿Eliminar usuario?',
            text: 'Esta acción no se puede deshacer',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('index.php?controller=personas&action=eliminar', { id: id }, function (resp) {
                    try {
                        var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                        if (r.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: r.msg,
                                showConfirmButton: false,
                                timer: 1400
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: r.msg
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado',
                            text: 'Ocurrió un error inesperado'
                        });
                    }
                });
            }
        });
    });
});