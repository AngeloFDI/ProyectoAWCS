<?php
class Database {
    public static function connect() {
        $host = 'localhost';
        $user = 'root';
        $pass = ''; // recuerden cambiar la contraeña por la de ustedes
        $db   = 'proyectoawcs'; // Cambia si tu base lleva otro nombre

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("Fallo al conectar la BD: " . $conn->connect_error);
        }
        return $conn;
    }
}

// esto lo quité del archivo config.inc.php: $cfg['Servers'][$i]['controluser'] = 'pma';