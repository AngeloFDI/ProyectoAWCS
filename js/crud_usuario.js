$(document).ready(function () {

    // Variables globales de búsqueda/paginado
    let paginaActual = 1;
    let busquedaActual = "";

    // --------- Listado, búsqueda y paginación AJAX ---------
    function cargarUsuarios(pagina, busqueda) {
        $.post(
            'index.php?controller=personas&action=ajax_listado',
            { pagina: pagina, busqueda: busqueda },
            function (resp) {
                try {
                    var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                    if (r.success) {
                        renderTabla(r.usuarios);
                        renderPaginacion(r.total, pagina);
                    } else {
                        $('#lista-personas').html('<div class="alert alert-danger">' + (r.msg || "Error") + '</div>');
                    }
                } catch (e) {
                    $('#lista-personas').html('Error inesperado');
                }
            }
        );
    }

    function renderTabla(usuarios) {
        let html = `<table class="tabla-personas table table-bordered"><thead>
            <tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Rol</th>
                <th>Sección</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>`;
        if (usuarios.length == 0) {
            html += `<tr><td colspan="8" class="text-center text-muted">No hay resultados</td></tr>`;
        } else {
            usuarios.forEach(function (u) {
                html += `<tr>
                   <td>${u.id_usuario}</td>
                   <td>${u.nombre}</td>
                   <td>${u.apellido}</td>
                   <td>${u.correo}</td>
                   <td>${u.rol}</td>
                   <td>${u.seccion || ''}</td>
                   <td>${u.estado_usuario == 1 ? 'Activo' : 'Inactivo'}</td>
                   <td>
                      <a href="index.php?controller=personas&action=editar_form&id=${u.id_usuario}" class="btn btn-info btn-sm">Editar</a>
                      <button class="btn btn-danger btn-sm borrarUsuario" data-id="${u.id_usuario}">Eliminar</button>
                   </td>
                </tr>`;
            });
        }
        html += `</tbody></table>`;
        $('#lista-personas').html(html);
    }

    function renderPaginacion(total, paginaActual, porPagina = 10) {
        let html = '';
        let paginas = Math.ceil(total / porPagina);
        if (paginas <= 1) {
            $('#paginacion-personas').html('');
            return;
        }
        for (let i = 1; i <= paginas; i++) {
            html += `<button class="btn btn-sm ${i == paginaActual ? 'btn-primary' : 'btn-light'} paginadorPersona" data-pagina="${i}">${i}</button> `;
        }
        $('#paginacion-personas').html(html);
    }

    // Buscar
    $('#form-buscar').submit(function (e) {
        e.preventDefault();
        const busqueda = $('#input-buscar').val().trim();
        paginaActual = 1;
        busquedaActual = busqueda;
        cargarUsuarios(paginaActual, busquedaActual);
    });

    // Paginación
    $(document).on('click', '.paginadorPersona', function () {
        let nuevaPagina = $(this).data('pagina');
        paginaActual = nuevaPagina;
        cargarUsuarios(paginaActual, busquedaActual);
    });

    // Carga inicial
    if ($("#lista-personas").length)
        cargarUsuarios(1, "");

    // --------- Crear / Editar con validaciones y SweetAlert ---------
    if ($('#form-usuario').length) {
        $('#form-usuario').submit(function (e) {
            e.preventDefault();

            // VALIDACIONES previas
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

    // --------- Eliminar usuario con SweetAlert ---------
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