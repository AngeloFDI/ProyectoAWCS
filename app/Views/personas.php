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
    <a href="index.php?controller=libros&action=index">Libros</a>
    <a href="index.php?controller=personas&action=index"class="active">Usuarios</a>
    <a href="index.php?controller=reserva&action=index">Reservas</a>
    <a href="index.php?controller=reportes&action=index">Reportes</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
  </nav>
  <section>
    <h1 id="titulo">Personas</h1>
    <div class="container">
      <button class="btn btn-success mb-2" onclick="window.location='index.php?controller=personas&action=crear_form'">
        <i class="bi bi-person-plus"></i> Crear Usuario
      </button>
      <table class="tabla-personas table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Sección</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td><?= $u['id_usuario'] ?></td>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['apellido']) ?></td>
            <td><?= htmlspecialchars($u['correo']) ?></td>
            <td><?= htmlspecialchars($u['rol']) ?></td>
            <td><?= htmlspecialchars($u['seccion']) ?></td>
            <td><?= $u['estado_usuario'] ? 'Activo' : 'Inactivo' ?></td>
            <td>
              <a href="index.php?controller=personas&action=editar_form&id=<?= $u['id_usuario'] ?>" class="btn btn-info btn-sm">Editar</a>
              <button class="btn btn-danger btn-sm borrarUsuario" data-id="<?= $u['id_usuario'] ?>">Eliminar</button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
  <footer>
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
  </footer>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/crud_usuario.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>