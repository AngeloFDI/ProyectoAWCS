<?php
if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Recursos</title>
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
    <a href="index.php?controller=tabletas&action=index">Tabletas</a>
    <a href="index.php?controller=recursos&action=libros">Libros</a>
    <a href="index.php?controller=personas&action=index">Usuarios</a>
    <a href="index.php?controller=reserva&action=index">Reservas</a>
    <a href="index.php?controller=reportes&action=index">Reportes</a>
    <a href="index.php?controller=recursos&action=index" class="active">Recursos</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">Gestión de Recursos</h1>
          <p class="lead mb-0">Administre los recursos de la biblioteca</p>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <!-- Filtros de búsqueda -->
      <div class="card mb-3">
        <div class="card-body">
          <form class="row g-3" id="form-buscar">
            <div class="col-md-6">
              <input class="form-control" type="search" placeholder="Buscar recurso por nombre" aria-label="Search" id="input-buscar">
            </div>
            <div class="col-md-6 d-flex justify-content-end gap-2">
              <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Buscar
              </button>
              <button class="btn btn-success" onclick="window.location='index.php?controller=recursos&action=crear_form'">
                <i class="bi bi-plus-circle"></i> Crear Recurso
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- Tabla de recursos -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lista de Recursos</h5>
        </div>
        <div class="card-body">
          <div id="lista-recursos"></div>
          <div id="paginacion-recursos" class="mt-3"></div>
        </div>
      </div>
    </div>
  </section>

  <footer>
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
  </footer>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/crud_recurso.js"></script>
</body>
</html>
