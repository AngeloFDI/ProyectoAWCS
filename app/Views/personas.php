<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Personas</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
  <nav class="navbar-main">
        <span style="float:right;">Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?controller=home&action=index">Inicio</a>
        <a href="index.php?controller=computadoras&action=index">Computadoras</a>
        <a href="index.php?controller=tabletas&action=index">Tabletas</a>
        <a href="index.php?controller=libros&action=index">Libros</a>
        <a href="index.php?controller=personas&action=index" class="active">Personas</a>
        <a href="index.php?controller=reserva&action=index">Reservas</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
  <section>
    <h1 id="titulo">Personas</h1>
    <div class="container">
      <nav class="navbar navbar-light bg-light">
        <form class="form-inline search-container">
          <input class="form-control" type="search" placeholder="Buscar persona" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Buscar</button>
          <button id="gestionar"> Gestionar Personas </button>
        </form>
      </nav>
    
      <table class="tabla-personas">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Número de Cédula</th>
            <th>Número Telefónico</th>
            <th>Correo Electrónico</th>
            <th>Cantidad de Reservas</th>
          </tr>
        </thead>
        <tbody>
          
          <tr>
            <td>Emily </td>
            <td>Cortés</td>
            <td>12345678</td>
            <td>8888-8888</td>
            <td>emily@gmail.com</td>
            <td>2</td>
          </tr>
        </tbody>
        <tbody>
          <tr>
            <td>Angelo</td>
            <td>Segura</td>
            <td>87654321</td>
            <td>7777-7777</td>
            <td>angelo@email.com</td>
            <td>2</td>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <td>Luis Angel</td>
            <td>Barquero</td>
            <td>11223344</td>
            <td>9999-9999</td>
            <td>luis@email.com</td>
            <td>1</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
  <footer> 
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>

</html>