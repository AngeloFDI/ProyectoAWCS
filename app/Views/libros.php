<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Libros</title>
  <link rel="stylesheet" href="app/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar-main">
        <span style="float:right; color: #fff; font-weight: bold;">Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?controller=home&action=index">Inicio</a>
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index">Tabletas</a>
        <a href="index.php?controller=libros&action=index" class="active">Libros</a>
        <a href="index.php?controller=personas&action=index">Personas</a>
        <a href="index.php?controller=reserva&action=index">Reservas</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
  <section>
    <h1 id="titulo">Libros</h1>
    <div class="container">
      <nav class="navbar navbar-light bg-light">
        <form class="form-inline search-container">
          <input class="form-control" type="search" placeholder="Nombre del libro" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Buscar</button>
          <button id="gestionar"> Gestionar Libros </button>
        </form>
      </nav>
    </div>
  </section>
  <section class="cards-container">
    <div class="card" style="width: 18rem;">
      <img src="https://images.librotea.com/uploads/media/2020/06/26/clasicos-diario-de-ana-frank.jpeg"
        class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">El Diario de Ana Frank</h4>
        <div class="card-body">
          <button> Reservar </button>
        </div>
      </div>
    </div>

    <div class="card" style="width: 18rem;">
      <img src="https://encantalibros.com/wp-content/uploads/2021/05/9788497592208.jpg" class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">Cien Años de Soledad</h4>
        <div class="card-body">
          <button> Reservar </button>
        </div>
      </div>
    </div>

    <div class="card" style="width: 18rem;">
      <img src="https://algareditorial.com/29329-thickbox_default/don-quijote.jpg" class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">Don Quijote de la Mancha</h4>
        <div class="card-body">
          <button> Reservar </button>
        </div>
      </div>
    </div>

    <div class="card" style="width: 18rem;">
      <img
        src="https://cdn.kobo.com/book-images/86caf6ab-35d2-4e48-8a0a-d28d4987f7fc/1200/1200/False/el-principito-45.jpg"
        class="card-img-top" alt="80px">
      <div class="card-body">
        <h4 class="card-title">El Principito</h4>
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