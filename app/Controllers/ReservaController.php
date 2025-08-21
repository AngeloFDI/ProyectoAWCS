<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../Models/Recurso.php');
require_once(__DIR__ . '/../Models/Usuario.php');

class ReservaController {
    
    // Mostrar vista de reservas
    public function index() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $usuario = $_SESSION['usuario'];
        
        define('IN_APP', true);
        include(__DIR__ . '/../Views/reservas.php');
    }
    
    // Obtener recursos disponibles por tipo (AJAX)
    public function obtenerRecursos() {
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
    
    // Crear una nueva reserva
    public function crear() {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        
        $id_recurso = intval($_POST['id_recurso'] ?? 0);
        $id_usuario = intval($_POST['id_usuario'] ?? 0);
        $fecha_reserva = $_POST['fecha_reserva'] ?? '';
        
        if (!$id_recurso || !$id_usuario || !$fecha_reserva) {
            echo json_encode(['success' => false, 'msg' => 'Todos los campos son requeridos']);
            return;
        }
        
        $resultado = $this->crearReserva($id_recurso, $id_usuario, $fecha_reserva);
        echo json_encode($resultado);
    }
    
    // Listar reservas del usuario
    public function listar() {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        
        $reservas = $this->obtenerReservasUsuario($id_usuario);
        echo json_encode(['success' => true, 'reservas' => $reservas]);
    }
    
    // Método privado para crear reserva en la base de datos
    private function crearReserva($id_recurso, $id_usuario, $fecha_reserva) {
        $db = Database::connect();
        
        // Verificar que el recurso existe y tiene disponibilidad
        $recurso = Recurso::buscarPorId($id_recurso);
        if (!$recurso) {
            return ['success' => false, 'msg' => 'Recurso no encontrado'];
        }
        
        if ($recurso['cantidad_disponible'] <= 0) {
            return ['success' => false, 'msg' => 'Recurso no disponible'];
        }
        
        // Verificar que el usuario existe
        $usuario = Usuario::buscarPorId($id_usuario);
        if (!$usuario) {
            return ['success' => false, 'msg' => 'Usuario no encontrado'];
        }
        
        // Verificar que no hay una reserva activa para este recurso en la misma fecha
        $fecha_reserva_esc = $db->real_escape_string($fecha_reserva);
        $sql_check = "SELECT id_prestamo FROM prestamo WHERE id_recurso = $id_recurso AND fecha_prestamo = '$fecha_reserva_esc' AND estado = 'activo'";
        $result_check = $db->query($sql_check);
        
        if ($result_check && $result_check->num_rows > 0) {
            return ['success' => false, 'msg' => 'El recurso ya está reservado para esta fecha'];
        }
        
        // Calcular fecha de devolución (7 días después)
        $fecha_devolucion = date('Y-m-d', strtotime($fecha_reserva . ' +7 days'));
        
        // Insertar la reserva
        $sql = "INSERT INTO prestamo (id_recurso, id_usuario, fecha_prestamo, fecha_devolucion, estado) 
                VALUES ($id_recurso, $id_usuario, '$fecha_reserva_esc', '$fecha_devolucion', 'activo')";
        
        if ($db->query($sql)) {
            // Actualizar cantidad disponible del recurso
            $nueva_cantidad = $recurso['cantidad_disponible'] - 1;
            Recurso::actualizarCantidad($id_recurso, $nueva_cantidad);
            
            return ['success' => true, 'msg' => 'Reserva creada exitosamente'];
        } else {
            return ['success' => false, 'msg' => 'Error al crear la reserva: ' . $db->error];
        }
    }
    
    // Método privado para obtener reservas de un usuario
    private function obtenerReservasUsuario($id_usuario) {
        $db = Database::connect();
        $id_usuario = intval($id_usuario);
        
        $sql = "SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion, p.estado,
                       r.nombre as nombre_recurso, r.tipo as tipo_recurso
                FROM prestamo p
                JOIN recurso r ON p.id_recurso = r.id_recurso
                WHERE p.id_usuario = $id_usuario
                ORDER BY p.fecha_prestamo DESC";
        
        $result = $db->query($sql);
        $reservas = [];
        
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        
        return $reservas;
    }
    
    // Gestionar reservas (para administradores)
    public function gestionar() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        // Verificar si es personal administrativo
        if ($_SESSION['usuario']['rol'] !== 'personal') {
            header('Location: index.php?controller=reserva&action=index');
            exit();
        }
        
        $usuario = $_SESSION['usuario'];
        
        define('IN_APP', true);
        include(__DIR__ . '/../Views/gestionar_reservas.php');
    }
    
    // Obtener todas las reservas (para personal administrativo)
    public function obtenerTodasReservas() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        
        $db = Database::connect();
        
        $sql = "SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion, p.estado,
                       r.nombre as nombre_recurso, r.tipo as tipo_recurso,
                       u.nombre as nombre_usuario, u.apellido as apellido_usuario
                FROM prestamo p
                JOIN recurso r ON p.id_recurso = r.id_recurso
                JOIN usuario u ON p.id_usuario = u.id_usuario
                ORDER BY p.fecha_prestamo DESC";
        
        $result = $db->query($sql);
        $reservas = [];
        
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        
        echo json_encode(['success' => true, 'reservas' => $reservas]);
    }
    
    // Cambiar estado de una reserva
    public function cambiarEstado() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'personal') {
            echo json_encode(['success' => false, 'msg' => 'Acceso denegado']);
            return;
        }
        
        $id_prestamo = intval($_POST['id_prestamo'] ?? 0);
        $nuevo_estado = $_POST['estado'] ?? '';
        
        if (!$id_prestamo || !$nuevo_estado) {
            echo json_encode(['success' => false, 'msg' => 'Datos requeridos']);
            return;
        }
        
        $db = Database::connect();
        $estado_esc = $db->real_escape_string($nuevo_estado);
        
        $sql = "UPDATE prestamo SET estado = '$estado_esc' WHERE id_prestamo = $id_prestamo";
        
        if ($db->query($sql)) {
            echo json_encode(['success' => true, 'msg' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Error al actualizar estado']);
        }
    }
    
}
