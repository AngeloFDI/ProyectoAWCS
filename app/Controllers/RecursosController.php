<?php
require_once(__DIR__ . '/../Models/Recurso.php');

class RecursosController {
    // Listar recursos (solo personal)
    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $recursos = Recurso::obtenerTodos();
        $usuario = $_SESSION['usuario'];
        define('IN_APP', true);
        include(__DIR__ . '/../Views/recursos.php');
    }

    // Mostrar formulario para crear recurso
    public function crear_form() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $modo = 'crear';
        $datos = null;
        define('IN_APP', true);
        include(__DIR__ . '/../Views/form_recurso.php');
    }

    // Procesar creación de recurso (por AJAX, responde JSON)
    public function crear() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado.']);
            return;
        }
        // Log para depuración
        error_log("Datos recibidos en crear(): " . print_r($_POST, true));
        $resp = Recurso::guardar($_POST);
        
        // Log para depuración
        error_log("Respuesta de guardar(): " . print_r($resp, true));
        echo json_encode($resp);
    }

    // Mostrar formulario de edición
    public function editar_form() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $datos = Recurso::buscarPorId($_GET['id'] ?? 0);
        if (!$datos) {
            header('Location: index.php?controller=recursos&action=index&error=No+existe+el+recurso');
            exit();
        }
        $modo = 'editar';
        define('IN_APP', true);
        include(__DIR__ . '/../Views/form_recurso.php');
    }

    // Procesar edición de recurso (por AJAX, responde JSON)
    public function editar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        $resp = Recurso::actualizar($_POST);
        echo json_encode($resp);
    }

    // Eliminar recurso (por AJAX)
    public function eliminar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        $id = $_POST['id'] ?? 0;
        $resp = Recurso::eliminar($id);
        echo json_encode($resp);
    }

    // Búsqueda y paginación (por AJAX)
    public function ajax_listado() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        $query = $_POST['busqueda'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
        $porPagina = 10;
        $data = Recurso::buscarPaginado($query, $tipo, $pagina, $porPagina);
        echo json_encode(['success' => true, 'recursos' => $data['recursos'], 'total' => $data['total']]);
    }

    // Obtener recursos por tipo (por AJAX)
    public function ajax_por_tipo() {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        $tipo = $_POST['tipo'] ?? '';
        if (empty($tipo)) {
            echo json_encode(['success' => false, 'msg' => 'Tipo de recurso requerido']);
            return;
        }
        $recursos = Recurso::obtenerPorTipo($tipo);
        echo json_encode(['success' => true, 'recursos' => $recursos]);
    }
    // Actualizar cantidad disponible (por AJAX)
    public function ajax_actualizar_cantidad() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado.']);
            return;
        }
        $id_recurso = $_POST['id_recurso'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 0;
        
        if (!$id_recurso || $cantidad < 0) {
            echo json_encode(['success' => false, 'msg' => 'Datos inválidos.']);
            return;
        }
        
        $resp = Recurso::actualizarCantidad($id_recurso, $cantidad);
        echo json_encode($resp);
    }

    // Vista pública de recursos (para estudiantes)
    public function catalogo() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login_form');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $tipo = $_GET['tipo'] ?? '';
        
        if ($tipo) {
            $recursos = Recurso::obtenerPorTipo($tipo);
        } else {
            $recursos = Recurso::obtenerTodos();
        }
        
        define('IN_APP', true);
        include(__DIR__ . '/../Views/catalogo_recursos.php');
    }

    // Vista específica por tipo de recurso
    public function libros() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login_form');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $libros = Recurso::obtenerPorTipo('Libro');
        define('IN_APP', true);
        include(__DIR__ . '/../Views/libros.php');
    }

    public function computadoras() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login_form');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $computadoras = Recurso::obtenerPorTipo('Computadora');
        define('IN_APP', true);
        include(__DIR__ . '/../Views/computadoras.php');
    }

    public function tabletas() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login_form');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        $tabletas = Recurso::obtenerPorTipo('Tableta');
        define('IN_APP', true);
        include(__DIR__ . '/../Views/tabletas.php');
    }
}
