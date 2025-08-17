<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section>
        <h1>Iniciar Sesión</h1>
        <?php if($mensaje): ?>
        <p style="color:red;"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=auth&action=login">
            <input type="email" name="correo" placeholder="Correo"><br><br>
            <input type="password" name="contrasena" placeholder="Contraseña"><br><br>
            <button type="submit">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="index.php?controller=auth&action=register_form">Regístrate aquí</a></p>
    </section>
    <footer>
        <i class="bi bi-facebook"> BiblioCra San José</i><br><br>
        <i class="bi bi-whatsapp"> +506 71234567</i><br><br>
        <i class="bi bi-book"> Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>

</html>