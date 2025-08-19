$(document).ready(function () {

    // Variables globales de búsqueda/paginado
    let paginaActual = 1;
    let busquedaActual = "";

    // --------- Listado, búsqueda y paginación AJAX ---------
    function cargarRecursos(pagina, busqueda) {
        $.post(
            'index.php?controller=recursos&action=ajax_listado',
            { pagina: pagina, busqueda: busqueda },
            function (resp) {
                try {
                    var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                    if (r.success) {
                        renderTabla(r.recursos);
                        renderPaginacion(r.total, pagina);
                    } else {
                        $('#lista-recursos').html('<div class="alert alert-danger">' + (r.msg || "Error") + '</div>');
                    }
                } catch (e) {
                    $('#lista-recursos').html('<div class="alert alert-danger">Error inesperado</div>');
                }
            }
        );
    }

    function renderTabla(recursos) {
        let html = `<div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>`;
        
        if (recursos.length == 0) {
            html += `<tr><td colspan="6" class="text-center text-muted">No hay recursos disponibles</td></tr>`;
        } else {
            recursos.forEach(function (r) {
                let imagen = r.ruta_imagen ? `<img src="${r.ruta_imagen}" alt="${r.nombre}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">` : '<span class="text-muted">Sin imagen</span>';
                let badgeClass = '';
                switch(r.tipo) {
                    case 'Libro': badgeClass = 'bg-primary'; break;
                    case 'Computadora': badgeClass = 'bg-success'; break;
                    case 'Tableta': badgeClass = 'bg-warning'; break;
                    default: badgeClass = 'bg-secondary';
                }
                
                html += `<tr>
                    <td>${r.id_recurso}</td>
                    <td><span class="badge ${badgeClass}">${r.tipo}</span></td>
                    <td>${r.nombre}</td>
                    <td>
                        <span class="badge ${r.cantidad_disponible > 0 ? 'bg-success' : 'bg-danger'}">${r.cantidad_disponible}</span>
                        <button class="btn btn-sm btn-outline-primary ms-2" onclick="editarCantidad(${r.id_recurso}, ${r.cantidad_disponible})">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                    <td>${imagen}</td>
                    <td>
                        <a href="index.php?controller=recursos&action=editar_form&id=${r.id_recurso}" class="btn btn-info btn-sm">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <button class="btn btn-danger btn-sm borrarRecurso" data-id="${r.id_recurso}" data-nombre="${r.nombre}">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </td>
                </tr>`;
            });
        }
        html += `</tbody></table></div>`;
        $('#lista-recursos').html(html);
    }

    function renderPaginacion(total, paginaActual, porPagina = 10) {
        let html = '';
        let paginas = Math.ceil(total / porPagina);
        if (paginas <= 1) {
            $('#paginacion-recursos').html('');
            return;
        }
        
        html += '<nav><ul class="pagination justify-content-center">';
        
        // Botón anterior
        if (paginaActual > 1) {
            html += `<li class="page-item"><button class="page-link paginadorRecurso" data-pagina="${paginaActual - 1}">Anterior</button></li>`;
        }
        
        // Números de página
        for (let i = 1; i <= paginas; i++) {
            if (i == paginaActual) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><button class="page-link paginadorRecurso" data-pagina="${i}">${i}</button></li>`;
            }
        }
        
        // Botón siguiente
        if (paginaActual < paginas) {
            html += `<li class="page-item"><button class="page-link paginadorRecurso" data-pagina="${paginaActual + 1}">Siguiente</button></li>`;
        }
        
        html += '</ul></nav>';
        $('#paginacion-recursos').html(html);
    }

    // Buscar
    $('#form-buscar').submit(function (e) {
        e.preventDefault();
        const busqueda = $('#input-buscar').val().trim();
        paginaActual = 1;
        busquedaActual = busqueda;
        cargarRecursos(paginaActual, busquedaActual);
    });

    // Paginación
    $(document).on('click', '.paginadorRecurso', function () {
        let nuevaPagina = $(this).data('pagina');
        paginaActual = nuevaPagina;
        cargarRecursos(paginaActual, busquedaActual);
    });

    // Carga inicial
    if ($("#lista-recursos").length) {
        cargarRecursos(1, "");
    }

    // --------- Crear / Editar con validaciones y SweetAlert ---------
    if ($('#form-recurso').length) {
        $('#form-recurso').submit(function (e) {
            e.preventDefault();

            // VALIDACIONES previas
            const tipo = $('select[name="tipo"]').val();
            const nombre = $('input[name="nombre"]').val().trim();
            const cantidad = $('input[name="cantidad_disponible"]').val();
            let modo = $(this).find('button[type="submit"]').html().toLowerCase().includes('crear') ? 'crear' : 'editar';

            let errores = [];
            if (!tipo) errores.push('El tipo de recurso es obligatorio');
            if (!nombre) errores.push('El nombre es obligatorio');
            if (!cantidad || cantidad < 0) errores.push('La cantidad debe ser mayor o igual a 0');

            if (errores.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el formulario',
                    html: errores.join('<br>')
                });
                return;
            }

            var datos = $(this).serialize();
            var url = 'index.php?controller=recursos&action=' + (modo == 'crear' ? 'crear' : 'editar');
            $.post(url, datos, function (resp) {
                console.log('Respuesta recibida:', resp);
                try {
                    var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                    console.log('Respuesta parseada:', r);
                    if (r.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: r.msg,
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => {
                            window.location = 'index.php?controller=recursos&action=index';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: r.msg
                        });
                    }
                } catch (e) {
                    console.error('Error al parsear respuesta:', e);
                    console.log('Respuesta original:', resp);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'Ocurrió un error inesperado: ' + e.message
                    });
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error al enviar datos: ' + error
                });
            });
        });
    }

    // --------- Eliminar recurso con SweetAlert ---------
    $(document).on('click', '.borrarRecurso', function () {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        Swal.fire({
            icon: 'warning',
            title: '¿Eliminar recurso?',
            text: `¿Estás seguro de eliminar "${nombre}"? Esta acción no se puede deshacer.`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('index.php?controller=recursos&action=eliminar', { id: id }, function (resp) {
                    try {
                        var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                        if (r.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: r.msg,
                                showConfirmButton: false,
                                timer: 1400
                            }).then(() => {
                                cargarRecursos(paginaActual, busquedaActual);
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
            }
        });
    });

    // --------- Editar cantidad disponible ---------
    window.editarCantidad = function(id, cantidadActual) {
        Swal.fire({
            title: 'Editar cantidad disponible',
            input: 'number',
            inputValue: cantidadActual,
            inputAttributes: {
                min: '0',
                step: '1'
            },
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value || value < 0) {
                    return 'La cantidad debe ser mayor o igual a 0';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('index.php?controller=recursos&action=ajax_actualizar_cantidad', {
                    id_recurso: id,
                    cantidad: result.value
                }, function (resp) {
                    try {
                        var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                        if (r.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: r.msg,
                                showConfirmButton: false,
                                timer: 1200
                            }).then(() => {
                                cargarRecursos(paginaActual, busquedaActual);
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
            }
        });
    };
});
