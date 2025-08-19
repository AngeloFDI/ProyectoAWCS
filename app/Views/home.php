<?php
if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario Biblioteca</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar-main">
        <span style="float:right; color: #fff; font-weight: bold;">
            Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?> (<?= htmlspecialchars($usuario['rol']) ?>)
        </span>
        <a href="index.php?controller=home&action=index" class="active">Inicio</a>
        <!-- Menú para PERSONAL -->
        <?php if ($usuario['rol'] === 'personal'): ?>
            <a href="index.php?controller=computadoras&action=index">Computadoras</a>
            <a href="index.php?controller=tabletas&action=index">Tabletas</a>
            <a href="index.php?controller=recursos&action=libros">Libros</a>
            <a href="index.php?controller=personas&action=index">Usuarios</a>
            <a href="index.php?controller=reserva&action=index">Reservas</a>
            <a href="index.php?controller=reportes&action=index">Reportes</a>
            <a href="index.php?controller=recursos&action=index">Recursos</a>
        <?php else: ?>
        <!-- Menú para ESTUDIANTE -->
            <a href="index.php?controller=computadoras&action=index">Computadoras</a>
            <a href="index.php?controller=tabletas&action=index">Tabletas</a>
            <a href="index.php?controller=recursos&action=libros">Libros</a>
            <a href="index.php?controller=reserva&action=index">Reservas</a>
        <?php endif; ?>
        <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
    <h1 id="titulo">Biblioteca del Liceo San José</h1>
    <section>
        <div class="container">
            <div id="biblioCarousel" class="carousel slide mx-auto mb-5" data-bs-ride="carousel"
                style="max-width:2200px; width:100%;">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#biblioCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#biblioCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#biblioCarousel" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner" style="border-radius: 18px;">
                    <div class="carousel-item active">
                        <img src="https://lsjbbtc.wordpress.com/wp-content/uploads/2021/03/p9110108.jpg?w=640" class="d-block mx-auto"
                            alt="Imagen1" style="width:100%; height:1000px; object-fit:contain; background:#fff;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://lsjbbtc.wordpress.com/wp-content/uploads/2021/03/p9110108.jpg?w=640" class="d-block mx-auto"
                            alt="Imagen2" style="width:100%; height:1000px; object-fit:contain; background:#fff;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://lsjbbtc.wordpress.com/wp-content/uploads/2021/03/p9110108.jpg?w=640" class="d-block mx-auto"
                            alt="Imagen3" style="width:100%; height:1000px; object-fit:contain; background:#fff;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#biblioCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#biblioCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
            
            <div class="cards-container">
                <!-- CARD para Computadoras -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Computadoras</h4>
                        <a href="index.php?controller=computadoras&action=index">
                            <button>Ir a Computadoras</button>
                        </a>
                    </div>
                </div>
                <!-- CARD para Tabletas -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tabletas</h4>
                        <a href="index.php?controller=tabletas&action=index">
                            <button>Ir a Tabletas</button>
                        </a>
                    </div>
                </div>
                <!-- CARD para Libros -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Libros</h4>
                        <a href="index.php?controller=libros&action=index">
                            <button>Ir a Libros</button>
                        </a>
                    </div>
                </div>
                <!-- CARD para Reservas -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reservas</h4>
                        <a href="index.php?controller=reserva&action=index">
                            <button>Reservar</button>
                        </a>
                    </div>
                </div>
                <!-- SOLO PARA PERSONAL: Usuarios y Reportes -->
                <?php if ($usuario['rol'] === 'personal'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Usuarios</h4>
                        <a href="index.php?controller=personas&action=index">
                            <button>Administrar Usuarios</button>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reportes</h4>
                        <a href="index.php?controller=reportes&action=index">
                            <button>Ver Reportes</button>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <!-- CARD para Editar Perfil -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Editar Perfil</h4>
                        <a href="index.php?controller=perfil&action=editar">
                            <button>Editar mi Perfil</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <i class="bi bi-facebook"> BiblioCra San José</i><br><br>
        <i class="bi bi-whatsapp"> +506 71234567</i><br><br>
        <i class="bi bi-book"> Biblioteca Liceo San José desde 1995</i>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>