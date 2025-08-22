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

    // Buscar en la tabla de reservas (filtro en frontend)
    $('.search-container').on('submit', function(e){
        e.preventDefault();
        var texto = $(this).find('input[type="search"]').val().trim().toLowerCase();
        if(texto.length === 0) {
            // Muestra todas las filas
            $('#lista-reservas table tbody tr').show();
        } else {
            $('#lista-reservas table tbody tr').each(function(){
                var encontrado = false;
                $(this).find('td').each(function(){
                    if ($(this).text().toLowerCase().includes(texto)) {
                        encontrado = true;
                    }
                });
                if(encontrado) $(this).show();
                else $(this).hide();
            });
        }
    });
    
    // Función para cargar mis reservas
    function cargarMisReservas() {
        $.post('index.php?controller=reserva&action=listar', function(resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                if (r.success) {
                    var html = '';
                    if (r.reservas.length === 0) {
                        html = '<div class="text-center py-4">';
                        html += '<i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>';
                        html += '<p class="text-muted mt-2">No tienes reservas activas</p>';
                        html += '</div>';
                    } else {
                        html = '<div class="table-responsive">';
                        html += '<table class="table table-striped table-hover">';
                        html += '<thead class="table-light">';
                        html += '<tr>';
                                                 html += '<th>Recurso</th>';
                         html += '<th>Tipo</th>';
                         html += '<th>Fecha Reserva</th>';
                         html += '<th>Fecha Devolución</th>';
                         html += '<th>Estado</th>';
                        html += '</tr>';
                        html += '</thead><tbody>';
                        
                        r.reservas.forEach(function(reserva) {
                            var estadoClass = '';
                            var estadoText = '';
                            
                            if (reserva.estado === 'activo') {
                                estadoClass = 'badge bg-success';
                                estadoText = 'Activa';
                            } else if (reserva.estado === 'completada') {
                                estadoClass = 'badge bg-info';
                                estadoText = 'Completada';
                            } else if (reserva.estado === 'cancelada') {
                                estadoClass = 'badge bg-danger';
                                estadoText = 'Cancelada';
                            } else {
                                estadoClass = 'badge bg-secondary';
                                estadoText = reserva.estado;
                            }
                            
                            html += '<tr>';
                            html += '<td><strong>' + reserva.nombre_recurso + '</strong></td>';
                            html += '<td>' + reserva.tipo_recurso + '</td>';
                            html += '<td>' + reserva.fecha_prestamo + '</td>';
                                                         html += '<td>' + reserva.fecha_devolucion + '</td>';
                             html += '<td><span class="' + estadoClass + '">' + estadoText + '</span></td>';
                             html += '</tr>';
                        });
                        html += '</tbody></table></div>';
                    }
                    $('#lista-reservas').html(html);
                } else {
                    $('#lista-reservas').html('<div class="alert alert-danger">Error al cargar reservas</div>');
                }
            } catch (e) {
                $('#lista-reservas').html('<div class="alert alert-danger">Error al cargar reservas</div>');
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
