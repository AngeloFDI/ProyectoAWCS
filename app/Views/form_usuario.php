<?php
if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= ($modo === 'crear' ? 'Registrar Usuario' : 'Editar Usuario') ?></title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="register-section">
        <h1><?= ($modo === 'crear' ? 'Registrar Usuario' : 'Editar Usuario') ?></h1>
        <form id="form-usuario" class="register-form" autocomplete="off">
            <?php if ($modo === 'editar'): ?>
                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($datos['id_usuario']) ?>">
            <?php endif; ?>
            <label>Nombre*</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>"><br>
            <label>Apellido*</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($datos['apellido'] ?? '') ?>"><br>
            <label>Correo*</label>
            <input type="email" name="correo" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($datos['correo'] ?? '') ?>"><br>
            <label>Rol*</label>
            <select name="rol">
                <option value="estudiante" <?= (isset($datos['rol']) && $datos['rol']=='estudiante') ? 'selected':''; ?>>Estudiante</option>
                <option value="personal" <?= (isset($datos['rol']) && $datos['rol']=='personal') ? 'selected':''; ?>>Personal</option>
            </select><br>
            <label>Sección (opcional)</label>
            <input type="text" name="seccion" value="<?= htmlspecialchars($datos['seccion'] ?? '') ?>"><br>
            <label>Estado*</label>
            <select name="estado_usuario">
                <option value="1" <?= (!isset($datos['estado_usuario']) || $datos['estado_usuario']==1) ? 'selected':''; ?>>Activo</option>
                <option value="0" <?= (isset($datos['estado_usuario']) && $datos['estado_usuario']==0) ? 'selected':''; ?>>Inactivo</option>
            </select><br>
            <?php if ($modo === 'crear'): ?>
                <label>Contraseña*</label>
                <input type="password" name="contrasena" placeholder="Ingrese una contraseña"><br>
            <?php endif; ?>
            <button type="submit"><?= ($modo === 'crear' ? 'Registrar' : 'Guardar Cambios') ?></button>
            <button type="button" onclick="window.location='index.php?controller=personas&action=index';" class="btn btn-secondary">Volver</button>
        </form>
    </section>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/crud_usuario.js"></script>
</body>
</html>