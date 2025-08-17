
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="app/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/register.js"></script>
</head>
<body>
    <section class="register-section">
        <h1>Registro de Usuario</h1>
        <div id="error-msg"><?= $mensaje ? "<p style='color:red;'>$mensaje</p>" : '' ?></div>
        <form method="POST" action="index.php?controller=auth&action=register" class="register-form">
            <input type="text" name="nombre" placeholder="Nombre*"><br><br>
            <input type="text" name="apellido" placeholder="Apellido*"><br><br>
            <input type="email" name="correo" placeholder="Correo*"><br><br>
            <input type="password" name="contrasena" placeholder="Contraseña*"><br><br>
            <input type="password" id="confirm-password" placeholder="Repetir contraseña*"><br><br>
            <input type="text" name="seccion" placeholder="Sección (opcional)"><br><br>
            <button type="submit">Registrarse</button>
        </form>
        <p><a href="index.php?controller=auth&action=login_form">¿Ya tienes cuenta? Inicia sesión aquí</a></p>
    </section>
    <footer>
        <i class="bi bi-facebook"> BiblioCra San José</i><br><br>
        <i class="bi bi-whatsapp"> +506 71234567</i><br><br>
        <i class="bi bi-book"> Biblioteca Liceo San José desde 1995</i>
    </footer>
</body>
</html>