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
    // Agregar Métodos CRUD: guardar(), actualizar(), eliminar(), buscarPorId(), etc.
}