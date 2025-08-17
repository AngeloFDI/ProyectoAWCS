<?php
require_once(__DIR__ . '/../Models/Usuario.php');

class UsuarioController {
    public function index() {
        $usuarios = Usuario::obtenerTodos();
        define('IN_APP', true);
        include(__DIR__ . '/../Views/personas.php');
    }
    // Agregar Métodos create, update, delete
}