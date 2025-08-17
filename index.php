<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario Biblioteca</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <nav class="navbar-main">
        <a href="index.php" class="active">Inicio</a>
        <a href="app/Views/computadoras.php">Computadoras</a>
        <a href="app/Views/tabletas.php">Tabletas</a>
        <a href="app/Views/libros.php">Libros</a>
        <a href="app/Views/personas.php">Personas</a>
        <a href="app/Views/reservas.php">Reservas</a>
        <a href="app/Views/reportes.php">Reportes</a>
    </nav>
    <section>
        <h1 id="titulo">Biblioteca del Liceo San José</h1>
        <div class="container">
            <p>Bienvenido/a. Click para ir a una categoría:</p>
            <div class="cards-container">
                <div class="card" style="width: 18rem;">
                    <img src="https://img.freepik.com/vector-premium/diseno-plano-icono-portatil_23-2148083760.jpg"
                        alt="Computadoras" class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Computadoras</h4>
                        <a href="app/Views/computadoras.php">
                            <button>Ir a Computadoras</button>
                        </a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" alt="Tabletas"
                        class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Tabletas</h4>
                        <a href="app/Views/tabletas.php">
                            <button>Ir a Tabletas</button>
                        </a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" alt="Libros"
                        class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Libros</h4>
                        <a href="app/Views/libros.php">
                            <button>Ir a Libros</button>
                        </a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Personas"
                        class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Personas</h4>
                        <a href="app/Views/personas.php">
                            <button>Ir a Personas</button>
                        </a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" alt="Reservas"
                        class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Reservas</h4>
                        <a href="app/Views/reservas.php">
                            <button>Ir a Reservas</button>
                        </a>
                    </div>
                </div>
                <div class="card" style="width: 18rem;">
                    <img src="https://cdn-icons-png.flaticon.com/512/1828/1828919.png" alt="Reportes"
                        class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">Reportes</h4>
                        <a href="app/Views/reportes.php">
                            <button>Ir a Reportes</button>
                        </a>
                    </div>
                </div>
    </section>
    <footer> 
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>

</html>