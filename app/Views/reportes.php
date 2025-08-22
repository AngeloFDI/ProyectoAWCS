<?php
if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reportes</title>
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
        <a href="index.php?controller=recursos&action=libros">Libros</a>
        <a href="index.php?controller=personas&action=index">Personas</a>
        <a href="index.php?controller=reportes&action=index" class="active">Reportes</a>
        <a href="index.php?controller=recursos&action=index">Recursos</a>
        <a href="index.php?controller=reserva&action=index">Reservas</a>
        <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>
    <section>
    <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold mb-3">Reportes de Uso y Devoluciones</h1>
          <p class="lead mb-0">Genere reportes para el seguimiento de préstamos de libros, tabletas y computadoras</p>
        </div>
      </div>
    </div>
  </section>
        <div class="container">
            <nav class="navbar navbar-light bg-light">
                <form class="form-inline search-container">
                    <input class="form-control" type="search" placeholder="Buscar en reportes" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
            </nav>
        </div>
        <div class="reportes-container">
            <h4>Utilice los filtros para personalizar el reporte</h4>

            <form class="reporte-form">
            <select id="tipo-recurso">
                    <option value="todos">Todos</option>
                    <option value="libros">Libros</option>
                    <option value="computadoras">Computadoras</option>
                    <option value="tabletas">Tabletas</option>
                </select>
                <input type="date" id="fecha-inicio">
                <input type="date" id="fecha-fin">
                <button type="button" id="btn-generar">Generar reporte</button>
                <button type="button" id="exportar-excel" class="btn btn-success mb-2">
                    <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                </button> 
            </form>

            <div class="table-responsive">
            <table id="tabla-reportes" class="table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Recurso</th>
                            <th>Persona</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas dinámicas aquí -->
                    </tbody>
                </table>
            </div>
          
        </div>
    </section>
    <footer> 
      <i class="bi bi-facebook">  BiblioCra San José</i><br><br>
      <i class="bi bi-whatsapp">  +506 71234567</i><br><br>
      <i class="bi bi-book">  Biblioteca Liceo San José desde 1995</i>
    </footer>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script>
$(document).ready(function() {
    $('#btn-generar').click(function() {
        var tipo = $('#tipo-recurso').val();
        var fecha_inicio = $('#fecha-inicio').val();
        var fecha_fin = $('#fecha-fin').val();
        $.post('index.php?controller=reportes&action=generar', {
            tipo: tipo,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin
        }, function(resp) {
            try {
                var r = (typeof resp === "object") ? resp : JSON.parse(resp);
                var html = '';
                if (r.success && r.reportes.length > 0) {
                    r.reportes.forEach(function(rep) {
                        html += '<tr>';
                        html += '<td>' + rep.tipo + '</td>';
                        html += '<td>' + rep.recurso + '</td>';
                        html += '<td>' + rep.usuario + ' ' + rep.apellido + '</td>';
                        html += '<td>' + rep.fecha_prestamo + '</td>';
                        html += '<td>' + rep.fecha_devolucion + '</td>';
                        html += '<td>' + rep.estado + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="6">No hay resultados</td></tr>';
                }
                $('#tabla-reportes tbody').html(html);
            } catch (e) {
                $('#tabla-reportes tbody').html('<tr><td colspan="6">Error al cargar reportes</td></tr>');
            }
        });
    });

    $('#exportar-excel').click(function() {
        var table = document.getElementById('tabla-reportes');
        var html = table.outerHTML.replace(/ /g, '%20');
        var a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + html;
        a.download = 'reporte.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});
</script>
</body>

</html>