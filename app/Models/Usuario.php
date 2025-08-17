<?php
require_once(__DIR__ . '/../config/db.php');

class Usuario {
    public $id_usuario, $nombre, $apellido, $correo, $contrasena, $rol, $seccion, $fecha_registro, $estado_usuario;

    public static function obtenerTodos() {
        $db = Database::connect();
        $result = $db->query("SELECT * FROM usuario");
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

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

    public static function buscarPorId($id) {
        $db = Database::connect();
        $id = intval($id);
        $result = $db->query("SELECT * FROM usuario WHERE id_usuario=$id");
        return $result->fetch_assoc();
    }

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
}