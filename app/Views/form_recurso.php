<?php
if (!defined('IN_APP')) {
    die('Acceso denegado.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= ($modo === 'crear' ? 'Crear Recurso' : 'Editar Recurso') ?></title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar-main">
        <span>
            Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>
        </span>
        <a href="index.php?controller=home&action=index">Inicio</a>
        <a href="index.php?controller=recursos&action=computadoras">Computadoras</a>
        <a href="index.php?controller=recursos&action=tabletas">Tabletas</a>
        <a href="index.php?controller=recursos&action=libros">Libros</a>
        <a href="index.php?controller=personas&action=index">Usuarios</a>
        <a href="index.php?controller=reserva&action=index">Reservas</a>
        <a href="index.php?controller=reportes&action=index">Reportes</a>
        <a href="index.php?controller=recursos&action=index" class="active">Recursos</a>
        <a href="index.php?controller=perfil&action=editar">Mi Perfil</a>
        <a href="index.php?controller=auth&action=logout"><button>Cerrar sesión</button></a>
    </nav>

    <div class="form-container">
        <div class="text-center mb-4">
            <h2 class="mb-2">
                <i class="bi bi-<?= ($modo === 'crear' ? 'plus-circle' : 'pencil-square') ?>"></i>
                <?= ($modo === 'crear' ? 'Crear Nuevo Recurso' : 'Editar Recurso') ?>
            </h2>
            <p class="text-muted">Complete los campos requeridos para <?= ($modo === 'crear' ? 'crear' : 'actualizar') ?> el recurso</p>
        </div>

        <form id="form-recurso" autocomplete="off">
            <?php if ($modo === 'editar'): ?>
                <input type="hidden" name="id_recurso" value="<?= htmlspecialchars($datos['id_recurso']) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="tipo">
                    Tipo de Recurso <span class="required">*</span>
                </label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Libro" <?= (isset($datos['tipo']) && $datos['tipo']=='Libro') ? 'selected':''; ?>>
                        Libro
                    </option>
                    <option value="Computadora" <?= (isset($datos['tipo']) && $datos['tipo']=='Computadora') ? 'selected':''; ?>>
                        Computadora
                    </option>
                    <option value="Tableta" <?= (isset($datos['tipo']) && $datos['tipo']=='Tableta') ? 'selected':''; ?>>
                        Tableta
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="nombre">
                    Nombre del Recurso <span class="required">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" class="form-control" 
                       value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>" 
                       placeholder="Ej: Laptop Dell Inspiron, Libro de Matemáticas, iPad Pro" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="cantidad_disponible">
                    Cantidad Disponible <span class="required">*</span>
                </label>
                <input type="number" name="cantidad_disponible" id="cantidad_disponible" class="form-control" 
                       value="<?= htmlspecialchars($datos['cantidad_disponible'] ?? '1') ?>" 
                       min="0" step="1" required>
                <small class="form-text text-muted">Ingrese la cantidad disponible para préstamo</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="ruta_imagen">
                    Ruta de Imagen (Opcional)
                </label>
                <input type="text" name="ruta_imagen" id="ruta_imagen" class="form-control" 
                       value="<?= htmlspecialchars($datos['ruta_imagen'] ?? '') ?>" 
                       placeholder="Ej: images/laptop.jpg o URL de imagen">
                <small class="form-text text-muted">Puede ser una ruta local o URL de imagen</small>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="button" onclick="window.location='index.php?controller=recursos&action=index';" 
                        class="btn btn-secondary me-md-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-<?= ($modo === 'crear' ? 'plus-circle' : 'check-circle') ?>"></i>
                    <?= ($modo === 'crear' ? 'Crear Recurso' : 'Guardar Cambios') ?>
                </button>
            </div>
        </form>
    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/crud_recurso.js"></script>
</body>
</html>
