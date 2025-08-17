<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Tabletas</title>
  <link rel="stylesheet" href="app/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar-main">
        <span style="float:right; color: #fff; font-weight: bold;">Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?controller=home&action=index">Inicio</a>
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index" class="active">Tabletas</a>
        <a href="index.php?controller=libros&action=index">Libros</a>
        <a href="index.php?controller=personas&action=index">Personas</a>
        <a href="index.php?controller=reserva&action=index">Reservas</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
  <section>
    <h1 id="titulo">Tabletas</h1>
    <div class="container">
      <nav class="navbar navbar-light bg-light">
        <form class="form-inline search-container">
          <input class="form-control" type="search" placeholder="Buscar tableta" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Buscar</button>
          <button id="gestionar"> Gestionar Tabletas </button>
        </form>
      </nav>
    </div>
  </section>

  <section class="cards-container">
    <div class="card" style="width: 18rem;">
      <img src="https://m.media-amazon.com/images/I/61d46oYQgdL._AC_SL1500_.jpg"
        class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">Samsung Galaxy Tab A9+</h4>
        <ul class="Especificaciones">
          <li>Sistema Operativo: Android</li>
          <li>RAM: 4GB</li>
          <li>Almacenamiento: 64GB</li>
          <li>Pantalla: 11"</li>
        </ul>
        <div class="card-body">
          <button> Reservar </button>
        </div>
      </div>
    </div>
  

  
    <div class="card" style="width: 18rem;">
      <img src="https://m.media-amazon.com/images/I/61d46oYQgdL._AC_SL1500_.jpg"
        class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">Samsung Galaxy Tab A9+</h4>
        <ul class="Especificaciones">
          <li>Sistema Operativo: Android</li>
          <li>RAM: 4GB</li>
          <li>Almacenamiento: 64GB</li>
          <li>Pantalla: 11"</li>
        </ul>
        <div class="card-body">
          <button> Reservar </button>
        </div>
      </div>
    </div>


  
    <div class="card" style="width: 18rem;">
      <img src="https://m.media-amazon.com/images/I/61d46oYQgdL._AC_SL1500_.jpg"
        class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">Samsung Galaxy Tab A9+</h4>
        <ul class="Especificaciones">
          <li>Sistema Operativo: Android</li>
          <li>RAM: 4GB</li>
          <li>Almacenamiento: 64GB</li>
          <li>Pantalla: 11"</li>
        </ul>
        <div class="card-body">
          <button> Reservar </button>
        </div>
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