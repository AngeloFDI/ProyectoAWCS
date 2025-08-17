<?php
require_once(__DIR__ . '/../Models/Usuario.php');

class AuthController {
    // Mostrar formulario de login
    public function login_form() {
        $mensaje = isset($_GET['error']) ? $_GET['error'] : '';
        include(__DIR__ . '/../Views/login.php');
    }

    // Mostrar formulario de registro
    public function register_form() {
        $mensaje = isset($_GET['error']) ? $_GET['error'] : '';
        include(__DIR__ . '/../Views/register.php');
    }
    
    // Procesar login de usuario
    public function login() {
        require_once(__DIR__ . '/../config/db.php');
        $conn = Database::connect();

        $correo = $conn->real_escape_string($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        $query = "SELECT * FROM usuario WHERE correo='$correo' AND estado_usuario=1";
        $result = $conn->query($query);

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Valida password (usa password_verify si la contraseña está hasheada)
            if (password_verify($contrasena, $user['contrasena'])) {
                $_SESSION['usuario'] = [
                    'id' => $user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'rol' => $user['rol']
                ];
                header("Location: index.php");
                exit();
            } else {
                header("Location: index.php?controller=auth&action=login_form&error=" . urlencode('Contraseña incorrecta'));
                exit();
            }
        } else {
            header("Location: index.php?controller=auth&action=login_form&error=" . urlencode('Usuario no encontrado o inactivo'));
            exit();
        }
        exit();
    }

    // Procesar registro de usuario
    public function register() {
        require_once(__DIR__ . '/../config/db.php');
        $conn = Database::connect();

        $nombre = trim($conn->real_escape_string($_POST['nombre'] ?? ''));
        $apellido = trim($conn->real_escape_string($_POST['apellido'] ?? ''));
        $correo = trim($conn->real_escape_string($_POST['correo'] ?? ''));
        $contrasena_raw = $_POST['contrasena'] ?? '';
        $contrasena = password_hash($contrasena_raw, PASSWORD_DEFAULT);
        $rol = "estudiante";
        $seccion = trim($conn->real_escape_string($_POST['seccion'] ?? ''));
        $fecha_registro = date('Y-m-d');
        $estado_usuario = 1;

        // Validar campos obligatorios
        if (!$nombre || !$apellido || !$correo || !$contrasena_raw) {
            $mensaje = "Todos los campos marcados son obligatorios.";
            header("Location: index.php?controller=auth&action=register_form&error=" . urlencode($mensaje));
            exit();
        }

        // Validar correo único
        $q = $conn->query("SELECT id_usuario FROM usuario WHERE correo='$correo'");
        if ($q && $q->num_rows > 0) {
            $mensaje = "El correo ya está registrado.";
            header("Location: index.php?controller=auth&action=register_form&error=" . urlencode($mensaje));
            exit();
        }

        // Insertar usuario
        $sql = "INSERT INTO usuario (nombre, apellido, correo, contrasena, rol, seccion, fecha_registro, estado_usuario)
                VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$rol', '$seccion', '$fecha_registro', $estado_usuario)";
        if ($conn->query($sql)) {
            // Login automático
            $user_id = $conn->insert_id;
            $res2 = $conn->query("SELECT * FROM usuario WHERE id_usuario=$user_id LIMIT 1");
            if($user = $res2->fetch_assoc()) {
                $_SESSION['usuario'] = [
                    'id' => $user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'rol' => $user['rol']
                ];
                header("Location: index.php");
                exit();
            }
        } else {
            $mensaje = "Error al registrar: " . $conn->error;
            header("Location: index.php?controller=auth&action=register_form&error=" . urlencode($mensaje));
            exit();
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login_form");
        exit();
    }
}
?>