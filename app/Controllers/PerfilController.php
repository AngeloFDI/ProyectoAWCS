<?php
class PerfilController
{
    // Mostrar el formulario para editar perfil
    public function editar()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=auth&action=login_form");
            exit();
        }
        require_once(__DIR__ . '/../config/db.php');
        $conn = Database::connect();
        $id = $_SESSION['usuario']['id_usuario'];
        $sql = "SELECT * FROM usuario WHERE id_usuario=$id";
        $result = $conn->query($sql);
        $usuario = $result->fetch_assoc();
        define('IN_APP', true);
        include(__DIR__ . '/../Views/editar_perfil.php');
    }

    // Procesar la actualización del perfil (AJAX, responde JSON)
    public function actualizar()
    {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Sesión expirada, por favor vuelva a ingresar.']);
            return;
        }
        require_once(__DIR__ . '/../config/db.php');
        $conn = Database::connect();
        $id = $_SESSION['usuario']['id_usuario'];

        $nombre = trim($conn->real_escape_string($_POST['nombre'] ?? ''));
        $apellido = trim($conn->real_escape_string($_POST['apellido'] ?? ''));
        $correo = trim($conn->real_escape_string($_POST['correo'] ?? ''));
        $seccion = trim($conn->real_escape_string($_POST['seccion'] ?? ''));
        $mensaje = '';

        // Para contraseña
        $pass1 = $_POST['contrasena_nueva'] ?? '';
        $pass2 = $_POST['contrasena_confirmar'] ?? '';

        // Validación básica
        if (!$nombre || !$apellido || !$correo) {
            echo json_encode(['success' => false, 'msg' => 'Todos los campos marcados son obligatorios.']);
            return;
        }
        // Verifica si el correo ya existe para otro usuario
        $sqlCheck = "SELECT id_usuario FROM usuario WHERE correo='$correo' AND id_usuario != $id";
        $resCheck = $conn->query($sqlCheck);
        if ($resCheck && $resCheck->num_rows > 0) {
            echo json_encode(['success' => false, 'msg' => 'Ese correo ya está siendo usado por otro usuario.']);
            return;
        }

        // Si quiere cambiar contraseña, valida igualdad/min longitud
        if ($pass1 || $pass2) {
            if ($pass1 !== $pass2) {
                echo json_encode(['success' => false, 'msg' => 'Las contraseñas no coinciden.']);
                return;
            }
            if (strlen($pass1) < 6) {
                echo json_encode(['success' => false, 'msg' => 'La contraseña debe tener al menos 6 caracteres.']);
                return;
            }
            $pass_hashed = password_hash($pass1, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', correo='$correo', seccion='$seccion', contrasena='$pass_hashed'
                    WHERE id_usuario=$id";
        } else {
            $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', correo='$correo', seccion='$seccion'
                    WHERE id_usuario=$id";
        }

        if ($conn->query($sql)) {
            $_SESSION['usuario']['nombre'] = $nombre; // para bienvenida
            echo json_encode(['success' => true, 'msg' => 'Perfil actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Error al actualizar: ' . $conn->error]);
        }
    }
}