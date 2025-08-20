<?php

if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Catálogo de Computadoras</title>
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
    <a href="index.php?controller=computadoras&action=index" class="active">Computadoras</a>
    <a href="index.php?controller=tabletas&action=index">Tabletas</a>
    <a href="index.php?controller=recursos&action=libros">Libros</a>
    <?php if ($usuario['rol'] === 'personal'): ?>
      <a href="index.php?controller=personas&action=index">Usuarios</a>
      <a href="index.php?controller=reserva&action=index">Reservas</a>
      <a href="index.php?controller=reportes&action=index">Reportes</a>
      <a href="index.php?controller=recursos&action=index">Recursos</a>
    <?php endif; ?>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-laptop"></i> Catálogo de Computadoras
          </h1>
          <p class="lead mb-0">Consulta las computadoras disponibles para préstamo</p>
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
            <form id="form-buscar-computadoras">
              <div class="input-group">
                <input type="text" class="form-control" id="input-buscar-computadoras" 
                       placeholder="Buscar computadoras por nombre" aria-label="Buscar computadoras">
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
          <h3 class="mb-0" id="total-computadoras">0</h3>
          <p class="mb-0">Total de Computadoras</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="computadoras-disponibles">0</h3>
          <p class="mb-0">Disponibles</p>
        </div>
        <div class="col-md-4">
          <h3 class="mb-0" id="computadoras-prestadas">0</h3>
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

    <div id="computadoras-container" class="row g-4">
      <!-- Las computadoras se cargarán dinámicamente aquí -->
    </div>

    <div class="no-results" id="no-results-computadoras" style="display: none;">
      <i class="bi bi-search"></i>
      <h4>No se encontraron computadoras</h4>
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
      let computadoras = [];
      let busquedaActual = "";

      cargarComputadoras();

      $('#form-buscar-computadoras').submit(function(e) {
        e.preventDefault();
        busquedaActual = $('#input-buscar-computadoras').val().trim();
        cargarComputadoras();
      });

      function cargarComputadoras() {
        $.post('index.php?controller=recursos&action=ajax_por_tipo', {
          tipo: 'Computadora'
        }, function(resp) {
          try {
            var r = (typeof resp === "object") ? resp : JSON.parse(resp);
            if (r.success) {
              computadoras = r.recursos;
              filtrarYMostrarComputadoras();
              actualizarEstadisticas();
            } else {
              mostrarError('Error al cargar las computadoras: ' + r.msg);
            }
          } catch (e) {
            mostrarError('Error inesperado al cargar las computadoras');
          }
        });
      }

      function filtrarYMostrarComputadoras() {
        let computadorasFiltradas = computadoras;
        if (busquedaActual) {
          computadorasFiltradas = computadoras.filter(compu => 
            compu.nombre.toLowerCase().includes(busquedaActual.toLowerCase())
          );
        }

        if (computadorasFiltradas.length === 0) {
          $('#no-results-computadoras').show();
          $('#computadoras-container').hide();
          return;
        }

        renderizarComputadoras(computadorasFiltradas);
        $('#computadoras-container').show();
        $('#no-results-computadoras').hide();
      }

      function renderizarComputadoras(computadoras) {
        let html = '';
        computadoras.forEach(function(compu) {
          const disponible = compu.cantidad_disponible > 0;
          const imagen = compu.ruta_imagen || '';
          html += `
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="card computadora-card">
                <div class="position-relative">
                  ${disponible ? 
                    '<span class="badge bg-success availability-badge">Disponible</span>' : 
                    '<span class="badge bg-danger availability-badge">No disponible</span>'
                  }
                  ${imagen ? 
                    `<img src="${imagen}" class="card-img-top computadora-image" alt="${compu.nombre}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                    ''
                  }
                  <div class="computadora-image-placeholder" style="display: ${imagen ? 'none' : 'flex'};">
                    <i class="bi bi-laptop" style="font-size: 3rem;"></i>
                  </div>
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">${compu.nombre}</h5>
                  <p class="card-text text-muted">
                    <i class="bi bi-collection"></i> Cantidad: ${compu.cantidad_disponible}
                  </p>
                  <div class="mt-auto">
                    <button class="btn btn-reserve w-100" 
                            onclick="reservarComputadora(${compu.id_recurso}, '${compu.nombre}')"
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
        $('#computadoras-container').html(html);
      }

      function actualizarEstadisticas() {
        const total = computadoras.length;
        const disponibles = computadoras.filter(c => c.cantidad_disponible > 0).length;
        const prestadas = total - disponibles;

        $('#total-computadoras').text(total);
        $('#computadoras-disponibles').text(disponibles);
        $('#computadoras-prestadas').text(prestadas);
      }

      function mostrarError(mensaje) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: mensaje
        });
      }
    });

    // Función global para reservar computadora
    function reservarComputadora(idComputadora, nombreComputadora) {
      Swal.fire({
        title: 'Reservar Computadora',
        text: `¿Deseas reservar "${nombreComputadora}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, reservar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Aquí se implementaría la lógica de reserva
          Swal.fire({
            icon: 'success',
            title: '¡Reserva exitosa!',
            text: `Has reservado "${nombreComputadora}"`,
            showConfirmButton: false,
            timer: 2000
          });
        }
      });
    }
  </script>
</body>