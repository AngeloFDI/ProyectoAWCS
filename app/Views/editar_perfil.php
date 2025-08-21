<?php
if (!defined('IN_APP')) { die('Acceso denegado.'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar mi Perfil</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="register-section">
        <h1>Editar mi Perfil</h1>
        <div id="alerta-perfil"></div>

        <form method="POST" id="editar-perfil" class="register-form" autocomplete="off">
            <label>Nombre*</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"><br>
            <label>Apellido*</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>"><br>
            <label>Correo*</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>"><br>
            <label>Sección (opcional)</label>
            <input type="text" name="seccion" value="<?= htmlspecialchars($usuario['seccion'] ?? '') ?>"><br>
            <label>Nueva Contraseña (opcional)</label>
            <input type="password" name="contrasena_nueva" placeholder="Nueva contraseña">
            <label>Repetir Nueva Contraseña (opcional)</label>
            <input type="password" name="contrasena_confirmar" placeholder="Repetir nueva contraseña"><br>
            <button type="submit">Guardar Cambios</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="index.php?controller=home&action=index" id="volver-inicio">Volver al inicio</a>
        </div>
    </section>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/editar_perfil.js"></script>
</body>
</html>