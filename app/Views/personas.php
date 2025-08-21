<?php
if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Personas</title>
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
    <a href="index.php?controller=recursos&action=libros">Libros</a>
    <a href="index.php?controller=personas&action=index" class="active">Usuarios</a>
    <a href="index.php?controller=reserva&action=index">Reservas</a>
    <a href="index.php?controller=reportes&action=index">Reportes</a>
    <a href="index.php?controller=recursos&action=index">Recursos</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">Gestión de Usuarios</h1>
          <p class="lead mb-0">Administre los usuarios de la biblioteca</p>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <button class="btn btn-success mb-2" onclick="window.location='index.php?controller=personas&action=crear_form'">
        <i class="bi bi-person-plus"></i> Crear Usuario
      </button>
      <nav class="navbar navbar-light bg-light mb-2">
        <form class="form-inline search-container" id="form-buscar">
          <input class="form-control" type="search" placeholder="Buscar persona" aria-label="Search" id="input-buscar">
          <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>
      </nav>
      <div id="lista-personas"></div>
      <div id="paginacion-personas" class="mt-2"></div>
    </div>
  </section>
  <footer>
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
  </footer>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/crud_usuario.js"></script>
</body>
</html>