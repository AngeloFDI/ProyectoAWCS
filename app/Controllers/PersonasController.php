<?php
require_once(__DIR__ . '/../Models/Usuario.php');

class PersonasController {
    // Listar usuarios (solo personal)
    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuarios = Usuario::obtenerTodos();
        $usuario = $_SESSION['usuario'];
        define('IN_APP', true);
        include(__DIR__ . '/../Views/personas.php');
    }

    // Mostrar formulario para crear usuario
    public function crear_form() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $modo = 'crear';
        $datos = null;
        define('IN_APP', true);
        include(__DIR__ . '/../Views/form_usuario.php');
    }

    // Procesar creación de usuario (por AJAX, responde JSON)
    public function crear() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado.']);
            return;
        }
        $resp = Usuario::guardar($_POST);
        echo json_encode($resp);
    }

    // Mostrar formulario de edición
    public function editar_form() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $datos = Usuario::buscarPorId($_GET['id'] ?? 0);
        if (!$datos) {
            header('Location: index.php?controller=personas&action=index&error=No+existe+el+usuario');
            exit();
        }
        $modo = 'editar';
        define('IN_APP', true);
        include(__DIR__ . '/../Views/form_usuario.php');
    }

    // Procesar edición de usuario (por AJAX, responde JSON)
    public function editar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado.']);
            return;
        }
        $resp = Usuario::actualizar($_POST);
        echo json_encode($resp);
    }

    // Eliminar usuario (por AJAX)
    public function eliminar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado.']);
            return;
        }
        $id = $_POST['id'] ?? 0;
        $resp = Usuario::eliminar($id);
        echo json_encode($resp);
    }
}