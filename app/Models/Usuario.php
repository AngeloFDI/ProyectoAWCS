<?php
require_once(__DIR__ . '/../config/db.php');

class Usuario {
    public $id_usuario, $nombre, $apellido, $correo, $contrasena, $rol, $seccion, $fecha_registro, $estado_usuario;

    // Obtener todos los usuarios
    public static function obtenerTodos() {
        $db = Database::connect();
        $result = $db->query("SELECT * FROM usuario");
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

    // Guardar nuevo usuario
    public static function guardar($post) {
        $db = Database::connect();
        $nombre = $db->real_escape_string($post['nombre'] ?? '');
        $apellido = $db->real_escape_string($post['apellido'] ?? '');
        $correo = $db->real_escape_string($post['correo'] ?? '');
        $rol = $db->real_escape_string($post['rol'] ?? 'estudiante');
        $seccion = $db->real_escape_string($post['seccion'] ?? '');
        $contrasena = password_hash($post['contrasena'] ?? '', PASSWORD_DEFAULT);

        // Revisa correo único
        $r = $db->query("SELECT id_usuario FROM usuario WHERE correo='$correo'");
        if ($r && $r->num_rows > 0) {
            return ['success' => false, 'msg' => 'Correo duplicado'];
        }

        $sql = "INSERT INTO usuario (nombre, apellido, correo, contrasena, rol, seccion, fecha_registro, estado_usuario)
                VALUES ('$nombre', '$apellido', '$correo', '$contrasena', '$rol', '$seccion', NOW(), 1)";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Usuario registrado correctamente'];
        } else {
            return ['success' => false, 'msg' => 'Error al crear usuario: '.$db->error];
        }
    }

    // Buscar usuario por ID
    public static function buscarPorId($id) {
        $db = Database::connect();
        $id = intval($id);
        $result = $db->query("SELECT * FROM usuario WHERE id_usuario=$id");
        return $result->fetch_assoc();
    }

    // Actualizar usuario
    public static function actualizar($post) {
        $db = Database::connect();
        $id = intval($post['id_usuario']);
        $nombre = $db->real_escape_string($post['nombre'] ?? '');
        $apellido = $db->real_escape_string($post['apellido'] ?? '');
        $correo = $db->real_escape_string($post['correo'] ?? '');
        $rol = $db->real_escape_string($post['rol'] ?? 'estudiante');
        $seccion = $db->real_escape_string($post['seccion'] ?? '');
        $estado_usuario = intval($post['estado_usuario'] ?? 1);

        // Si cambia correo, verifica que no se repita
        $r = $db->query("SELECT id_usuario FROM usuario WHERE correo='$correo' AND id_usuario!=$id");
        if ($r && $r->num_rows > 0) {
            return ['success' => false, 'msg' => 'Correo duplicado'];
        }

        $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', correo='$correo', rol='$rol', seccion='$seccion', estado_usuario=$estado_usuario WHERE id_usuario=$id";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Usuario actualizado'];
        } else {
            return ['success' => false, 'msg' => 'Error: '.$db->error];
        }
    }

    // Eliminar usuario
    public static function eliminar($id) {
        $db = Database::connect();
        $id = intval($id);
        $sql = "DELETE FROM usuario WHERE id_usuario=$id";
        if ($db->query($sql)) {
            return ['success' => true, 'msg' => 'Usuario eliminado'];
        } else {
            return ['success' => false, 'msg' => 'Error al borrar: '.$db->error];
        }
    }

    // Búsqueda y paginación de usuarios
    public static function buscarPaginado($query = '', $pagina = 1, $porPagina = 10) {
        $db = Database::connect();
        $query = $db->real_escape_string($query);
        $offset = ($pagina - 1) * $porPagina;

        // Búsqueda por nombre, apellido o correo (ajusta campos según tu tabla)
        $where = $query ? "WHERE nombre LIKE '%$query%' OR apellido LIKE '%$query%' OR correo LIKE '%$query%'" : "";

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario $where LIMIT $offset, $porPagina";
        $result = $db->query($sql); // CORREGIDO: faltaba el '$' delante de 'result'
        $resultados = [];
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
        // Total de resultados para paginación
        $totalResult = $db->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];
        return ['usuarios' => $resultados, 'total' => $totalResult];
    }
}