
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="app/css/style.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/register.js"></script>
</head>
<body>
    <section>
        <h1>Registro de Usuario</h1>
        <div id="error-msg"><?= $mensaje ? "<p style='color:red;'>$mensaje</p>" : '' ?></div>
        <form method="POST" action="index.php?controller=auth&action=register">
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
</body>
</html>