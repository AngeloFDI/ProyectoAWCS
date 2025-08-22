<?php
if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Catálogo de Libros</title>
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
    <a href="index.php?controller=computadoras&action=index">Computadoras</a>
    <a href="index.php?controller=tabletas&action=index">Tabletas</a>
    <a href="index.php?controller=recursos&action=libros" class="active">Libros</a>
    <?php if ($usuario['rol'] === 'personal'): ?>
      <a href="index.php?controller=personas&action=index">Usuarios</a>
      <a href="index.php?controller=reportes&action=index">Reportes</a>
      <a href="index.php?controller=recursos&action=index">Recursos</a>
    <?php endif; ?>
    <a href="index.php?controller=reserva&action=index">Reservas</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-book"></i> Catálogo de Libros
          </h1>
          <p class="lead mb-0">Explore nuestra colección de libros disponibles para préstamo</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Barra de búsqueda -->
  <div class="container mb-4">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <form id="form-buscar-libros">
              <div class="input-group">
                <input type="text" class="form-control" id="input-buscar-libros" 
                       placeholder="Buscar libros por título" aria-label="Buscar libros">
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
          <h3 class="mb-0" id="total-libros">0</h3>
          <p class="mb-0">Total de Libros</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="libros-disponibles">0</h3>
          <p class="mb-0">Disponibles</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="libros-prestados">0</h3>
          <p class="mb-0">En Préstamo</p>
        </div>
      </div>
    </div>

    <!-- Botón de gestión (solo para personal) -->
    <?php if ($usuario['rol'] === 'personal'): ?>
      <div class="text-end mb-3">
        <a href="index.php?controller=recursos&action=index" class="btn btn-primary">
          <i class="bi bi-gear"></i> Gestionar Recursos
        </a>
      </div>
    <?php endif; ?>
    <!-- Contenedor de libros -->
    <div id="libros-container" class="row g-4">
      <!-- Los libros se cargarán dinámicamente aquí -->
    </div>

    <!-- Mensaje de no resultados -->
    <div class="no-results" id="no-results" style="display: none;">
      <i class="bi bi-search"></i>
      <h4>No se encontraron libros</h4>
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
      let libros = [];
      let busquedaActual = "";

      // Cargar libros al inicio
      cargarLibros();

      // Búsqueda de libros
      $('#form-buscar-libros').submit(function(e) {
        e.preventDefault();
        busquedaActual = $('#input-buscar-libros').val().trim();
        cargarLibros();
      });

      function cargarLibros() {
        $.post('index.php?controller=recursos&action=ajax_por_tipo', {
          tipo: 'Libro'
        }, function(resp) {
          try {
            var r = (typeof resp === "object") ? resp : JSON.parse(resp);
            if (r.success) {
              libros = r.recursos;
              filtrarYMostrarLibros();
              actualizarEstadisticas();
            } else {
              mostrarError('Error al cargar los libros: ' + r.msg);
            }
          } catch (e) {
            mostrarError('Error inesperado al cargar los libros');
          }
        });
      }

      function filtrarYMostrarLibros() {
        let librosFiltrados = libros;
        
        if (busquedaActual) {
          librosFiltrados = libros.filter(libro => 
            libro.nombre.toLowerCase().includes(busquedaActual.toLowerCase())
          );
        }

        if (librosFiltrados.length === 0) {
          $('#no-results').show();
          $('#libros-container').hide();
          return;
        }

        renderizarLibros(librosFiltrados);
        $('#libros-container').show();
        $('#no-results').hide();
      }

      function renderizarLibros(libros) {
        let html = '';
        
        libros.forEach(function(libro) {
          const disponible = libro.cantidad_disponible > 0;
          const imagen = libro.ruta_imagen || '';
          
          html += `
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="card book-card">
                <div class="position-relative">
                  ${disponible ? 
                    '<span class="badge bg-success availability-badge">Disponible</span>' : 
                    '<span class="badge bg-danger availability-badge">No disponible</span>'
                  }
                  ${imagen ? 
                    `<img src="${imagen}" class="card-img-top book-image" alt="${libro.nombre}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                    ''
                  }
                  <div class="book-image-placeholder" style="display: ${imagen ? 'none' : 'flex'};">
                    <i class="bi bi-book" style="font-size: 3rem;"></i>
                  </div>
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">${libro.nombre}</h5>
                  <p class="card-text text-muted">
                    <i class="bi bi-collection"></i> Cantidad: ${libro.cantidad_disponible}
                  </p>
                  <div class="mt-auto">
                    <button class="btn btn-reserve w-100" 
                            onclick="reservarLibro(${libro.id_recurso}, '${libro.nombre}')"
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

        $('#libros-container').html(html);
      }

      function actualizarEstadisticas() {
        const total = libros.length;
        const disponibles = libros.filter(l => l.cantidad_disponible > 0).length;
        const prestados = total - disponibles;

        $('#total-libros').text(total);
        $('#libros-disponibles').text(disponibles);
        $('#libros-prestados').text(prestados);
      }

      function mostrarError(mensaje) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: mensaje
        });
      }
    });

    // Función global para reservar libro
    function reservarLibro(idLibro, nombreLibro) {
      // Obtener la fecha actual
      const fechaActual = new Date().toISOString().split('T')[0];
      
      Swal.fire({
        title: 'Reservar Libro',
        html: `
          <p>¿Deseas reservar "${nombreLibro}"?</p>
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
            id_recurso: idLibro,
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
                  // Recargar los libros para actualizar disponibilidad
                  cargarLibros();
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