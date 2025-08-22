<?php
require_once(__DIR__ . '/../config/db.php');

class ReportesController {
    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $usuario = $_SESSION['usuario'];
        define('IN_APP', true);
        include(__DIR__ . '/../Views/reportes.php');
    }

    public function generar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        $tipo = $_POST['tipo'] ?? 'todos';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        $db = Database::connect();
        if (!$db) {
            echo json_encode(['success' => false, 'msg' => 'Error de conexión a la base de datos']);
            return;
        }

        $where = [];
        $tipo = strtolower($tipo);
        $tipos_map = [
            'libros' => 'Libro',
            'computadoras' => 'Computadora',
            'tabletas' => 'Tableta'
        ];
        if (isset($tipos_map[$tipo])) {
            $tipo_db = $tipos_map[$tipo];
            $where[] = "r.tipo = '" . $db->real_escape_string($tipo_db) . "'";
        }
        if ($fecha_inicio) {
            $where[] = "p.fecha_prestamo >= '" . $db->real_escape_string($fecha_inicio) . "'";
        }
        if ($fecha_fin) {
            $where[] = "p.fecha_prestamo <= '" . $db->real_escape_string($fecha_fin) . "'";
        }
        $whereSQL = $where ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT p.id_prestamo, r.tipo, r.nombre as recurso, 
                       u.nombre as usuario, u.apellido as apellido,
                       p.fecha_prestamo, p.fecha_devolucion, p.estado
                FROM prestamo p
                JOIN recurso r ON p.id_recurso = r.id_recurso
                JOIN usuario u ON p.id_usuario = u.id_usuario
                $whereSQL
                ORDER BY p.fecha_prestamo DESC";
        
       error_log($sql);

        $result = $db->query($sql);
        if (!$result) {
            echo json_encode(['success' => false, 'msg' => 'Error en la consulta SQL']);
            return;
        }
        $reportes = [];
        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }
        echo json_encode(['success' => true, 'reportes' => $reportes]);
    }
}