<?php
if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reservas</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar-main">
        <span style="float:right; color: #fff; font-weight: bold;">Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?controller=home&action=index">Inicio</a>
    <!-- Menú para PERSONAL -->
    <?php if ($usuario['rol'] === 'personal'): ?>
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index">Tabletas</a>
        <a href="index.php?controller=recursos&action=libros">Libros</a>
        <a href="index.php?controller=personas&action=index">Usuarios</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=recursos&action=index">Recursos</a>
    <?php else: ?>
        <!-- Menú para ESTUDIANTE -->
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index">Tabletas</a>
        <a href="index.php?controller=recursos&action=libros">Libros</a>
    <?php endif; ?>
    <a href="index.php?controller=reserva&action=index" class="active">Reservas</a>
    <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
    <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
    <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">Reservas</h1>
          <p class="lead mb-0">Realice sus reservas</p>
        </div>
      </div>
    </div>
  </section>
    <section>
        <div class="container">
            <nav class="navbar navbar-light bg-light">
                <form class="form-inline search-container">
                    <input class="form-control" type="search" placeholder="Buscar en reservas" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                    <?php if ($usuario['rol'] === 'personal'): ?>
                        <button id="gestionar"> Gestionar Reservas </button>
                    <?php endif; ?>
                </form><br>
                <div></div>
                <h4>Seleccione el tipo de recurso que desea reservar</h4>
                <div class="selector-container">
                    <button onclick="showForm('libro')">Libro</button>
                    <button onclick="showForm('tableta')">Tableta</button>
                    <button onclick="showForm('computadora')">Computadora</button>
                </div>

                <!-- Formulario para reservar LIBRO -->
                <div id="form-libro" class="form-section">
                    <form id="form-reserva-libro">
                        <label for="libro-nombre">Libro:</label>
                        <select id="libro-nombre" name="id_recurso" required>
                            <option value="">Cargando libros...</option>
                        </select>
                        <label for="fecha-libro">Fecha de reserva:</label>
                        <input type="date" id="fecha-libro" name="fecha_reserva" required>
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit">Reservar Libro</button>
                    </form>
                </div>

                <!-- Formulario para reservar TABLETA -->
                <div id="form-tableta" class="form-section">
                    <form id="form-reserva-tableta">
                        <label for="tableta-modelo">Modelo de tableta:</label>
                        <select id="tableta-modelo" name="id_recurso" required>
                            <option value="">Cargando tabletas...</option>
                        </select>
                        <label for="fecha-tableta">Fecha de reserva:</label>
                        <input type="date" id="fecha-tableta" name="fecha_reserva" required>
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit">Reservar Tableta</button>
                    </form>
                </div>

                <!-- Formulario para reservar COMPUTADORA -->
                <div id="form-computadora" class="form-section">
                    <form id="form-reserva-computadora">
                        <label for="computadora-modelo">Modelo de computadora:</label>
                        <select id="computadora-modelo" name="id_recurso" required>
                            <option value="">Cargando computadoras...</option>
                        </select>
                        <label for="fecha-computadora">Fecha de reserva:</label>
                        <input type="date" id="fecha-computadora" name="fecha_reserva" required>
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit">Reservar Computadora</button>
                    </form>
                </div>
        </div>
    </section>
        <section>
        <div class="container">
            <!-- Tabla de reservas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Mis Reservas</h5>
                </div>
                <div class="card-body">
                    <div id="lista-reservas"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Scripts necesarios -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/reservas.js"></script>
    <footer> 
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
    </footer>

    <style>
        .form-section {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-section.active {
            display: block;
        }
        .form-section form {
            max-width: 500px;
        }
        .form-section label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .form-section select,
        .form-section input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-section button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-section button:hover {
            background-color: #0056b3;
        }
        .selector-container {
            margin: 20px 0;
        }
        .selector-container button {
            margin-right: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .selector-container button:hover {
            background-color: #218838;
        }
    </style>
</body>

</html>