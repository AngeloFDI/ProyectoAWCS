<?php

class HomeController
{
    public function index()
    {
        // Verifica si el usuario está logueado
        if (!isset($_SESSION['usuario'])) {
            // Si no está logueado, se envia al login
            header("Location: index.php?controller=auth&action=login_form");
            exit();
        }

        // Pasar datos a la vista
        $usuario = $_SESSION['usuario'];

        // Incluye la vista del dashboard
        include(__DIR__ . '/../Views/home.php');
    }
}