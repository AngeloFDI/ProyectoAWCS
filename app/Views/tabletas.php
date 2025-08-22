<?php

if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Tabletas</title>
  <link rel="stylesheet" href="app/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar-main">
    <span style="float:right; color: #fff; font-weight: bold;">
      Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>
    </span>
    <a href="index.php?controller=home&action=index">Inicio</a>
    <a href="index.php?controller=recursos&action=computadoras">Computadoras</a>
    <a href="index.php?controller=tabletas&action=index" class="active">Tabletas</a>
    <a href="index.php?controller=recursos&action=libros">Libros</a>
    <?php if ($usuario['rol'] === 'personal'): ?>
      <a href="index.php?controller=personas&action=index">Usuarios</a>
      <a href="index.php?controller=reportes&action=index">Reportes</a>
      <a href="index.php?controller=recursos&action=index">Recursos</a>
    <?php endif; ?>
    <a href="index.php?controller=reserva&action=index">Reservas</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>

  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-tablet"></i> Catálogo de Tabletas
          </h1>
          <p class="lead mb-0">Consulta las tabletas disponibles para préstamo</p>
        </div>
      </div>
    </div>
  </section>

  <div class="container mb-4">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <form id="form-buscar-tabletas">
              <div class="input-group">
                <input type="text" class="form-control" id="input-buscar-tabletas" 
                       placeholder="Buscar tabletas por nombre" aria-label="Buscar tabletas">
                <button class="btn btn-primary" type="submit">
                  <i class="bi bi-search"></i> Buscar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <!-- Estadísticas -->
    <div class="stats-card">
      <div class="row text-center">
        <div class="col-md-4">
          <h3 class="mb-0" id="total-tabletas">0</h3>
          <p class="mb-0">Total de Tabletas</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="tabletas-disponibles">0</h3>
          <p class="mb-0">Disponibles</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="tabletas-prestadas">0</h3>
          <p class="mb-0">En Préstamo</p>
        </div>
      </div>
    </div>

    <?php if ($usuario['rol'] === 'personal'): ?>
      <div class="text-end mb-3">
        <a href="index.php?controller=recursos&action=index" class="btn btn-primary">
          <i class="bi bi-gear"></i> Gestionar Recursos
        </a>
      </div>
    <?php endif; ?>

    <div id="tabletas-container" class="row g-4">
      <!-- Las tabletas se cargarán dinámicamente aquí -->
    </div>

    <div class="no-results" id="no-results-tabletas" style="display: none;">
      <i class="bi bi-search"></i>
      <h4>No se encontraron tabletas</h4>
      <p>Intenta con otros términos de búsqueda</p>
    </div>
  </div>

  <footer>
    <i class="bi bi-facebook"> BiblioCra San José</i><br><br>
    <i class="bi bi-whatsapp"> +506 71234567</i><br><br>
    <i class="bi bi-book"> Biblioteca Liceo San José desde 1995</i>
  </footer>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
      let tabletas = [];
      let busquedaActual = "";

      cargarTabletas();

      $('#form-buscar-tabletas').submit(function(e) {
        e.preventDefault();
        busquedaActual = $('#input-buscar-tabletas').val().trim();
        cargarTabletas();
      });

      function cargarTabletas() {
        $.post('index.php?controller=recursos&action=ajax_por_tipo', {
          tipo: 'Tableta'
        }, function(resp) {
          try {
            var r = (typeof resp === "object") ? resp : JSON.parse(resp);
            if (r.success) {
              tabletas = r.recursos;
              filtrarYMostrarTabletas();
              actualizarEstadisticas();
            } else {
              mostrarError('Error al cargar las tabletas: ' + r.msg);
            }
          } catch (e) {
            mostrarError('Error inesperado al cargar las tabletas');
          }
        });
      }

      function filtrarYMostrarTabletas() {
        let tabletasFiltradas = tabletas;
        if (busquedaActual) {
          tabletasFiltradas = tabletas.filter(tabla => 
            tabla.nombre.toLowerCase().includes(busquedaActual.toLowerCase())
          );
        }

        if (tabletasFiltradas.length === 0) {
          $('#no-results-tabletas').show();
          $('#tabletas-container').hide();
          return;
        }

        renderizarTabletas(tabletasFiltradas);
        $('#tabletas-container').show();
        $('#no-results-tabletas').hide();
      }

      function renderizarTabletas(tabletas) {
        let html = '';
        tabletas.forEach(function(tabla) {
          const disponible = tabla.cantidad_disponible > 0;
          const imagen = tabla.ruta_imagen || '';
          html += `
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="card tablet-card">
                <div class="position-relative">
                  ${disponible ? 
                    '<span class="badge bg-success availability-badge">Disponible</span>' : 
                    '<span class="badge bg-danger availability-badge">No disponible</span>'
                  }
                  ${imagen ? 
                    `<img src="${imagen}" class="card-img-top tablet-image" alt="${tabla.nombre}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                    ''
                  }
                  <div class="tablet-image-placeholder" style="display: ${imagen ? 'none' : 'flex'};">
                    <i class="bi bi-tablet" style="font-size: 3rem;"></i>
                  </div>
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">${tabla.nombre}</h5>
                  <p class="card-text text-muted">
                    <i class="bi bi-collection"></i> Cantidad: ${tabla.cantidad_disponible}
                  </p>
                  <div class="mt-auto">
                    <button class="btn btn-reserve w-100" 
                            onclick="reservarTableta(${tabla.id_recurso}, '${tabla.nombre}')"
                            ${!disponible ? 'disabled' : ''}>
                      <i class="bi bi-calendar-check"></i>
                      ${disponible ? 'Reservar' : 'No disponible'}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;
        });
        $('#tabletas-container').html(html);
      }

      function actualizarEstadisticas() {
        const total = tabletas.length;
        const disponibles = tabletas.filter(t => t.cantidad_disponible > 0).length;
        const prestadas = total - disponibles;

        $('#total-tabletas').text(total);
        $('#tabletas-disponibles').text(disponibles);
        $('#tabletas-prestadas').text(prestadas);
      }

      function mostrarError(mensaje) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: mensaje
        });
      }
    });

    // Función global para reservar tableta
    function reservarTableta(idTableta, nombreTableta) {
      // Obtener la fecha actual
      const fechaActual = new Date().toISOString().split('T')[0];
      
      Swal.fire({
        title: 'Reservar Tableta',
        html: `
          <p>¿Deseas reservar "${nombreTableta}"?</p>
          <div class="form-group">
            <label for="fecha-reserva">Fecha de reserva:</label>
            <input type="date" id="fecha-reserva" class="form-control" value="${fechaActual}" min="${fechaActual}">
          </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, reservar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
          const fechaReserva = document.getElementById('fecha-reserva').value;
          if (!fechaReserva) {
            Swal.showValidationMessage('Por favor selecciona una fecha');
            return false;
          }
          return fechaReserva;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const fechaReserva = result.value;
          
          // Realizar la reserva
          $.post('index.php?controller=reserva&action=crear', {
            id_recurso: idTableta,
            id_usuario: <?= $usuario['id_usuario'] ?>,
            fecha_reserva: fechaReserva
          }, function(resp) {
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
                  // Recargar las tabletas para actualizar disponibilidad
                  cargarTabletas();
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
        }
      });
    }
  </script>
</body>
</html>