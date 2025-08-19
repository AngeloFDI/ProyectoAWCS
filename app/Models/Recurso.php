<?php
require_once(__DIR__ . '/../config/db.php');

class Recurso {
    public $id_recurso, $tipo, $nombre, $cantidad_disponible, $ruta_imagen;

    // Obtener todos los recursos
    public static function obtenerTodos() {
        $db = Database::connect();
        $result = $db->query("SELECT * FROM recurso ORDER BY tipo, nombre");
        $recursos = [];
        while ($row = $result->fetch_assoc()) {
            $recursos[] = $row;
        }
        return $recursos;
    }

    // Obtener recursos por tipo
    public static function obtenerPorTipo($tipo) {
        $db = Database::connect();
        $tipo = $db->real_escape_string($tipo);
        $result = $db->query("SELECT * FROM recurso WHERE tipo='$tipo' ORDER BY nombre");
        $recursos = [];
        while ($row = $result->fetch_assoc()) {
            $recursos[] = $row;
        }
        return $recursos;
    }

    // Guardar nuevo recurso
    public static function guardar($post) {
        $db = Database::connect();
        $tipo = $db->real_escape_string($post['tipo'] ?? '');
        $nombre = $db->real_escape_string($post['nombre'] ?? '');
        $cantidad_disponible = intval($post['cantidad_disponible'] ?? 1);
        $ruta_imagen = $db->real_escape_string($post['ruta_imagen'] ?? '');

        // Log para depuración
        error_log("Datos procesados en guardar(): tipo=$tipo, nombre=$nombre, cantidad=$cantidad_disponible, imagen=$ruta_imagen");

        // Validar que el nombre no esté duplicado para el mismo tipo
        $r = $db->query("SELECT id_recurso FROM recurso WHERE nombre='$nombre' AND tipo='$tipo'");
        if ($r && $r->num_rows > 0) {
            error_log("Error: Ya existe un recurso con ese nombre y tipo");
            return ['success' => false, 'msg' => 'Ya existe un recurso con ese nombre y tipo'];
        }

        $sql = "INSERT INTO recurso (tipo, nombre, cantidad_disponible, ruta_imagen)
                VALUES ('$tipo', '$nombre', $cantidad_disponible, '$ruta_imagen')";
        
        error_log("SQL a ejecutar: " . $sql);
        
        if ($db->query($sql)) {
            error_log("Recurso creado exitosamente");
            return ['success' => true, 'msg' => 'Recurso registrado correctamente'];
        } else {
            error_log("Error en la consulta SQL: " . $db->error);
            return ['success' => false, 'msg' => 'Error al crear recurso: '.$db->error];
        }
    }

    // Buscar recurso por ID
    public static function buscarPorId($id) {
        $db = Database::connect();
        $id = intval($id);
        $result = $db->query("SELECT * FROM recurso WHERE id_recurso=$id");
        return $result->fetch_assoc();
    }

    // Actualizar recurso
    public static function actualizar($post) {
        $db = Database::connect();
        $id = intval($post['id_recurso']);
        $tipo = $db->real_escape_string($post['tipo'] ?? '');
        $nombre = $db->real_escape_string($post['nombre'] ?? '');
        $cantidad_disponible = intval($post['cantidad_disponible'] ?? 1);
        $ruta_imagen = $db->real_escape_string($post['ruta_imagen'] ?? '');

        // Si cambia nombre o tipo, verifica que no se repita
        $r = $db->query("SELECT id_recurso FROM recurso WHERE nombre='$nombre' AND tipo='$tipo' AND id_recurso!=$id");
        if ($r && $r->num_rows > 0) {
            return ['success' => false, 'msg' => 'Ya existe un recurso con ese nombre y tipo'];
        }

        $sql = "UPDATE recurso SET tipo='$tipo', nombre='$nombre', cantidad_disponible=$cantidad_disponible, ruta_imagen='$ruta_imagen' WHERE id_recurso=$id";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Recurso actualizado correctamente'];
        } else {
            return ['success' => false, 'msg' => 'Error al actualizar: '.$db->error];
        }
    }

    // Eliminar recurso
    public static function eliminar($id) {
        $db = Database::connect();
        $id = intval($id);
        
        // Verificar si hay préstamos activos para este recurso
        $r = $db->query("SELECT id_prestamo FROM prestamo WHERE id_recurso=$id AND estado='activo'");
        if ($r && $r->num_rows > 0) {
            return ['success' => false, 'msg' => 'No se puede eliminar: hay préstamos activos para este recurso'];
        }

        $sql = "DELETE FROM recurso WHERE id_recurso=$id";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Recurso eliminado correctamente'];
        } else {
            return ['success' => false, 'msg' => 'Error al eliminar: '.$db->error];
        }
    }

    // Búsqueda y paginación de recursos
    public static function buscarPaginado($query = '', $tipo = '', $pagina = 1, $porPagina = 10) {
        $db = Database::connect();
        $query = $db->real_escape_string($query);
        $tipo = $db->real_escape_string($tipo);
        $offset = ($pagina - 1) * $porPagina;

        $where = [];
        if ($query) {
            $where[] = "nombre LIKE '%$query%'";
        }
        if ($tipo) {
            $where[] = "tipo='$tipo'";
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM recurso $whereClause ORDER BY tipo, nombre LIMIT $offset, $porPagina";
        $result = $db->query($sql);
        $resultados = [];
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
        
        // Total de resultados para paginación
        $totalResult = $db->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];
        return ['recursos' => $resultados, 'total' => $totalResult];
    }

    // Actualizar cantidad disponible
    public static function actualizarCantidad($id_recurso, $cantidad) {
        $db = Database::connect();
        $id_recurso = intval($id_recurso);
        $cantidad = intval($cantidad);
        
        if ($cantidad < 0) {
            return ['success' => false, 'msg' => 'La cantidad no puede ser negativa'];
        }
        
        $sql = "UPDATE recurso SET cantidad_disponible=$cantidad WHERE id_recurso=$id_recurso";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Cantidad actualizada correctamente'];
        } else {
            return ['success' => false, 'msg' => 'Error al actualizar cantidad: '.$db->error];
        }
    }
}
