<?php
class Database {
    public static function connect() {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db   = 'proyectoawcs'; // Cambia si tu base lleva otro nombre

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("Fallo al conectar la BD: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>