$(document).ready(function() {
    // Función para mostrar formularios
    window.showForm = function(tipo) {
        $('.form-section').removeClass('active');
        if (tipo === 'libro') {
            $('#form-libro').addClass('active');
            cargarRecursos('Libro', '#libro-nombre');
        } else if (tipo === 'tableta') {
            $('#form-tableta').addClass('active');
            cargarRecursos('Tableta', '#tableta-modelo');
        } else if (tipo === 'computadora') {
            $('#form-computadora').addClass('active');
            cargarRecursos('Computadora', '#computadora-modelo');
        }
    };

    // Función para cargar recursos por tipo
    function cargarRecursos(tipo, selector) {
        $.post('index.php?controller=reserva&action=obtenerRecursos', {
            tipo: tipo
        }, function(resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                if (r.success) {
                    var html = '<option value="">Seleccione un ' + tipo.toLowerCase() + '</option>';
                    r.recursos.forEach(function(recurso) {
                        if (recurso.cantidad_disponible > 0) {
                            html += '<option value="' + recurso.id_recurso + '">' + recurso.nombre + ' (Disponible: ' + recurso.cantidad_disponible + ')</option>';
                        }
                    });
                    $(selector).html(html);
                } else {
                    $(selector).html('<option value="">Error al cargar recursos</option>');
                }
            } catch (e) {
                $(selector).html('<option value="">Error al cargar recursos</option>');
            }
        });
    }

    // Manejar envío de formularios de reserva
    $('#form-reserva-libro, #form-reserva-tableta, #form-reserva-computadora').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var tipo = $(this).attr('id').replace('form-reserva-', '');
        
        $.post('index.php?controller=reserva&action=crear', formData, function(resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                if (r.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Reserva exitosa!',
                        text: r.msg,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Recargar recursos y reservas
                        showForm(tipo);
                        cargarMisReservas();
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
                    text: 'Ocurrió un error al procesar la reserva'
                });
            }
        });
    });

    // Función para cargar mis reservas
    function cargarMisReservas() {
        $.post('index.php?controller=reserva&action=listar', function(resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                if (r.success) {
                    var html = '';
                    if (r.reservas.length === 0) {
                        html = '<p class="text-muted">No tienes reservas activas</p>';
                    } else {
                        html = '<div class="table-responsive"><table class="table table-striped">';
                        html += '<thead><tr><th>Recurso</th><th>Tipo</th><th>Fecha Reserva</th><th>Fecha Devolución</th><th>Estado</th></tr></thead><tbody>';
                        r.reservas.forEach(function(reserva) {
                            var estadoClass = reserva.estado === 'activo' ? 'badge bg-success' : 'badge bg-secondary';
                            html += '<tr>';
                            html += '<td>' + reserva.nombre_recurso + '</td>';
                            html += '<td>' + reserva.tipo_recurso + '</td>';
                            html += '<td>' + reserva.fecha_prestamo + '</td>';
                            html += '<td>' + reserva.fecha_devolucion + '</td>';
                            html += '<td><span class="' + estadoClass + '">' + reserva.estado + '</span></td>';
                            html += '</tr>';
                        });
                        html += '</tbody></table></div>';
                    }
                    $('#lista-reservas').html(html);
                } else {
                    $('#lista-reservas').html('<p class="text-danger">Error al cargar reservas</p>');
                }
            } catch (e) {
                $('#lista-reservas').html('<p class="text-danger">Error al cargar reservas</p>');
            }
        });
    }

    // Cargar mis reservas al cargar la página
    cargarMisReservas();
    
    // Evento para el botón "Gestionar Reservas" (solo para personal administrativo)
    $('#gestionar').click(function(e) {
        e.preventDefault();
        window.location.href = 'index.php?controller=reserva&action=gestionar';
    });
});
