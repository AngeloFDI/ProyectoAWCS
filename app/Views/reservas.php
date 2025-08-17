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
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index">Tabletas</a>
        <a href="index.php?controller=libros&action=index">Libros</a>
        <a href="index.php?controller=personas&action=index">Personas</a>
        <a href="index.php?controller=reserva&action=index" class="active">Reservas</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
    <section>
        <h1 id="titulo">Reservas</h1>
        <div class="container">
            <nav class="navbar navbar-light bg-light">
                <form class="form-inline search-container">
                    <input class="form-control" type="search" placeholder="Buscar en reservas" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                    <button id="gestionar"> Gestionar Reservas </button>
                </form>
                <p>Seleccione el tipo de recurso que desea reservar:</p>
                <div class="selector-container">
                    <button onclick="showForm('libro')">Libro</button>
                    <button onclick="showForm('tableta')">Tableta</button>
                    <button onclick="showForm('computadora')">Computadora</button>
                </div>

                <!-- Formulario para reservar LIBRO -->
                <div id="form-libro" class="form-section">
                    <form>
                        <label for="libro-nombre">Libro:</label>
                        <select id="libro-nombre" required>
                            <option value="">Selecciona un libro</option>
                            <option>El Diario de Ana Frank</option>
                            <option>Cien Años de Soledad</option>
                            <option>Don Quijote de la Mancha</option>
                            <option>El Principito</option>
                        </select>
                        <label for="nombre-usuario-libro">Nombre del usuario:</label>
                        <input type="text" id="nombre-usuario-libro" required>
                        <label for="fecha-libro">Fecha de reserva:</label>
                        <input type="date" id="fecha-libro" required>
                        <button type="submit">Reservar Libro</button>
                    </form>
                </div>

                <!-- Formulario para reservar TABLETA -->
                <div id="form-tableta" class="form-section">
                    <form>
                        <label for="tableta-modelo">Modelo de tableta:</label>
                        <select id="tableta-modelo" required>
                            <option value="">Seleccione una tableta</option>
                            <option>iPad 10.2" 2021</option>
                            <option>Samsung Galaxy Tab S6 Lite</option>
                            <option>Lenovo Tab P11</option>
                        </select>
                        <label for="nombre-usuario-tableta">Nombre del usuario:</label>
                        <input type="text" id="nombre-usuario-tableta" required>
                        <label for="fecha-tableta">Fecha de reserva:</label>
                        <input type="date" id="fecha-tableta" required>
                        <button type="submit">Reservar Tableta</button>
                    </form>
                </div>

                <!-- Formulario para reservar COMPUTADORA -->
                <div id="form-computadora" class="form-section">
                    <form>
                        <label for="computadora-modelo">Modelo de computadora:</label>
                        <select id="computadora-modelo" required>
                            <option value="">Seleccione una computadora</option>
                            <option>HP EliteBook 840</option>
                            <option>Dell Latitude 5420</option>
                            <option>MacBook Air M1</option>
                        </select>
                        <label for="nombre-usuario-computadora">Nombre del usuario:</label>
                        <input type="text" id="nombre-usuario-computadora" required>
                        <label for="fecha-computadora">Fecha de reserva:</label>
                        <input type="date" id="fecha-computadora" required>
                        <button type="submit">Reservar Computadora</button>
                    </form>
                </div>

        </div>
    </section>
    <!-- Lógica para mostrar los formularios, luego será movida al backend -->
    <script>
        function showForm(tipo) {
            document.getElementById('form-libro').classList.remove('active');
            document.getElementById('form-tableta').classList.remove('active');
            document.getElementById('form-computadora').classList.remove('active');
            if (tipo === 'libro') document.getElementById('form-libro').classList.add('active');
            if (tipo === 'tableta') document.getElementById('form-tableta').classList.add('active');
            if (tipo === 'computadora') document.getElementById('form-computadora').classList.add('active');
        }
    </script>
    <footer> 
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>

</html>