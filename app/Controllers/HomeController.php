<?php
//session_start();

class HomeController
{
    public function index()
    {
        // Verifica si el usuario está logueado
        if (!isset($_SESSION['usuario'])) {
            // Si no está logueado, lo enviamos al login
            header("Location: index.php?controller=auth&action=login_form");
            exit();
        }

        // Puedes pasarle datos a la vista si lo deseas, por ejemplo el nombre del usuario:
        $usuario = $_SESSION['usuario'];

        // Incluye la vista del dashboard (ajusta la ruta si la tienes diferente)
        include(__DIR__ . '/../Views/home.php');
    }
}