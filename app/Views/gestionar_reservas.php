<?php
if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Reservas</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>
<nav class="navbar-main">
    <span style="float:right; color: #fff; font-weight: bold;">
      Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>
    </span>
    <a href="index.php?controller=home&action=index">Inicio</a>
    <a href="index.php?controller=computadoras&action=index" >Computadoras</a>
    <a href="index.php?controller=tabletas&action=index">Tabletas</a>
    <a href="index.php?controller=recursos&action=libros">Libros</a>
    <?php if ($usuario['rol'] === 'personal'): ?>
      <a href="index.php?controller=personas&action=index">Usuarios</a>
      <a href="index.php?controller=reserva&action=index" class="active">Reservas</a>
      <a href="index.php?controller=reportes&action=index">Reportes</a>
      <a href="index.php?controller=recursos&action=index">Recursos</a>
    <?php endif; ?>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>
    
    <div class="container">
        <h1>Gestionar Reservas</h1>
        <p class="text-muted">Panel de administración para gestionar todas las reservas del sistema</p>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="buscar-reserva" placeholder="Buscar por usuario o recurso...">
                    <button class="btn btn-outline-secondary" type="button" onclick="filtrarReservas()">Buscar</button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" onclick="cargarTodasReservas()">Actualizar Lista</button>
            </div>
        </div>
        
        <div id="alerta-gestion"></div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Recurso</th>
                        <th>Tipo</th>
                        <th>Fecha Reserva</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-reservas">
                    <tr>
                        <td colspan="8" class="text-center">Cargando reservas</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            cargarTodasReservas();
        });
        
        function cargarTodasReservas() {
            $.post('index.php?controller=reserva&action=obtenerTodasReservas', function(resp) {
                try {
                    var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                    if (r.success) {
                        mostrarReservas(r.reservas);
                    } else {
                        $('#tabla-reservas').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar reservas</td></tr>');
                    }
                } catch (e) {
                    $('#tabla-reservas').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar reservas</td></tr>');
                }
            });
        }
        
        function mostrarReservas(reservas) {
            if (reservas.length === 0) {
                $('#tabla-reservas').html('<tr><td colspan="8" class="text-center text-muted">No hay reservas registradas</td></tr>');
                return;
            }
            
            var html = '';
            reservas.forEach(function(reserva) {
                var estadoClass = '';
                switch(reserva.estado) {
                    case 'activo': estadoClass = 'estado-activo'; break;
                    case 'completado': estadoClass = 'estado-completado'; break;
                    case 'cancelado': estadoClass = 'estado-cancelado'; break;
                }
                
                html += '<tr>';
                html += '<td>' + reserva.id_prestamo + '</td>';
                html += '<td>' + reserva.nombre_usuario + ' ' + reserva.apellido_usuario + '</td>';
                html += '<td>' + reserva.nombre_recurso + '</td>';
                html += '<td>' + reserva.tipo_recurso + '</td>';
                html += '<td>' + reserva.fecha_prestamo + '</td>';
                html += '<td>' + reserva.fecha_devolucion + '</td>';
                html += '<td><span class="' + estadoClass + '">' + reserva.estado + '</span></td>';
                html += '<td>';
                if (reserva.estado === 'activo') {
                    html += '<button class="btn-estado btn-completado" onclick="cambiarEstado(' + reserva.id_prestamo + ', \'completado\')">Completar</button>';
                    html += '<button class="btn-estado btn-cancelado" onclick="cambiarEstado(' + reserva.id_prestamo + ', \'cancelado\')">Cancelar</button>';
                } else {
                    html += '<button class="btn-estado btn-activo" onclick="cambiarEstado(' + reserva.id_prestamo + ', \'activo\')">Activar</button>';
                }
                html += '</td>';
                html += '</tr>';
            });
            
            $('#tabla-reservas').html(html);
        }
        
        function cambiarEstado(idPrestamo, nuevoEstado) {
            Swal.fire({
                title: '¿Confirmar cambio?',
                text: '¿Estás seguro de que quieres cambiar el estado a "' + nuevoEstado + '"?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('index.php?controller=reserva&action=cambiarEstado', {
                        id_prestamo: idPrestamo,
                        estado: nuevoEstado
                    }, function(resp) {
                        try {
                            var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                            if (r.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: r.msg,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    cargarTodasReservas();
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
                                text: 'Ocurrió un error al cambiar el estado'
                            });
                        }
                    });
                }
            });
        }
        
        function filtrarReservas() {
            var busqueda = $('#buscar-reserva').val().toLowerCase();
            $('#tabla-reservas tr').each(function() {
                var texto = $(this).text().toLowerCase();
                if (texto.includes(busqueda)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        // Filtrar al escribir en el campo de búsqueda
        $('#buscar-reserva').on('keyup', function() {
            filtrarReservas();
        });
    </script>
    <style>
       .container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.table-responsive {
  margin-top: 20px;
}

.estado-activo {
  background-color: #d4edda;
  color: #155724;
  padding: 4px 8px;
  border-radius: 4px;
}

.estado-completado {
  background-color: #d1ecf1;
  color: #0c5460;
  padding: 4px 8px;
  border-radius: 4px;
}

.estado-cancelado {
  background-color: #f8d7da;
  color: #721c24;
  padding: 4px 8px;
  border-radius: 4px;
}

.btn-estado {
  margin: 2px;
  padding: 4px 8px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-activo {
  background-color: #28a745;
  color: white;
}

.btn-completado {
  background-color: #17a2b8;
  color: white;
}

.btn-cancelado {
  background-color: #dc3545;
  color: white;
}
    </style>
</body>
</html>
