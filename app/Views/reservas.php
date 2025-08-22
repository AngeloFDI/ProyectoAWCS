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
        <span style="float:right; color: #fff; font-weight: bold;">Bienvenido,
            <?= htmlspecialchars($usuario['nombre']) ?></span>
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
    <section>
        <h1 id="titulo">Reservas</h1>
        <div class="container reserva-wrapper my-5 py-4">
            <div class="row justify-content-center gx-5">
                <!-- Formulario de reserva -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm p-4 mb-3">
                        <form class="row g-2 align-items-center mb-3" id="form-buscar-reservas">
                            <div class="col-12 col-sm-8">
                                <input class="form-control w-100" type="search" placeholder="Buscar en reservas"
                                    aria-label="Search">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-outline-success" type="submit">Buscar</button>
                            </div>
                            <?php if ($usuario['rol'] === 'personal'): ?>
                            <div class="col-auto">
                                <button id="gestionar" class="btn btn-warning">Gestionar Reservas</button>
                            </div>
                            <?php endif; ?>
                        </form>
                        <div class="mb-3">
                            <label class="fw-bold">Seleccione el tipo de recurso a reservar:</label>
                            <div class="selector-container d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-primary" onclick="showForm('libro')">Libro</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="showForm('tableta')">Tableta</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="showForm('computadora')">Computadora</button>
                            </div>
                        </div>
                        <!-- Formulario Libro -->
                        <div id="form-libro" class="form-section">
                            <form id="form-reserva-libro">
                                <label for="libro-nombre">Libro:</label>
                                <select id="libro-nombre" name="id_recurso" class="form-select mb-2" required>
                                    <option value="">Cargando libros...</option>
                                </select>
                                <label for="fecha-libro">Fecha de reserva:</label>
                                <input type="date" id="fecha-libro" name="fecha_reserva" class="form-control mb-2"
                                    required>
                                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                <button type="submit" class="btn btn-success w-100">Reservar Libro</button>
                            </form>
                        </div>
                        <!-- Formulario Tableta -->
                        <div id="form-tableta" class="form-section">
                            <form id="form-reserva-tableta">
                                <label for="tableta-modelo">Modelo de tableta:</label>
                                <select id="tableta-modelo" name="id_recurso" class="form-select mb-2" required>
                                    <option value="">Cargando tabletas...</option>
                                </select>
                                <label for="fecha-tableta">Fecha de reserva:</label>
                                <input type="date" id="fecha-tableta" name="fecha_reserva" class="form-control mb-2"
                                    required>
                                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                <button type="submit" class="btn btn-success w-100">Reservar Tableta</button>
                            </form>
                        </div>
                        <!-- Formulario Computadora -->
                        <div id="form-computadora" class="form-section">
                            <form id="form-reserva-computadora">
                                <label for="computadora-modelo">Modelo de computadora:</label>
                                <select id="computadora-modelo" name="id_recurso" class="form-select mb-2" required>
                                    <option value="">Cargando computadoras...</option>
                                </select>
                                <label for="fecha-computadora">Fecha de reserva:</label>
                                <input type="date" id="fecha-computadora" name="fecha_reserva" class="form-control mb-2"
                                    required>
                                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                <button type="submit" class="btn btn-success w-100">Reservar Computadora</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Lista de reservas del usuario -->
                <div class="col-lg-7">
                    <div class="card shadow-sm p-4 h-100">
                        <h3 class="mb-3">Mis Reservas</h3>
                        <div id="lista-reservas">
                            <!-- Aquí se cargará la tabla de reservas -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Scripts necesarios -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/reservas.js"></script>
    <footer>
        <i class="bi bi-facebook"> BiblioCra San José</i><br><br>
        <i class="bi bi-whatsapp"> +506 71234567</i><br><br>
        <i class="bi bi-book"> Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>

</html>