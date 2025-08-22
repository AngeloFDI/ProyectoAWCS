<?php
session_start();

$controller = $_GET['controller'] ?? $_POST['controller'] ?? null;
$action = $_GET['action'] ?? $_POST['action'] ?? null;

if (!$controller) {
    if (isset($_SESSION['usuario'])) {
        $controller = 'home';  // Controlador principal tras login (dashboard)
        $action = 'index';
    } else {
        $controller = 'auth';  // login y registro van en AuthController
        $action = 'login_form';
    }
}
if (!$action) {
    $action = 'index';
}

$controllerFile = 'app/Controllers/' . ucfirst($controller) . 'Controller.php';
if (file_exists($controllerFile)) {
    require_once($controllerFile);
    $controllerClass = ucfirst($controller) . 'Controller';
    $cont = new $controllerClass();
    if (method_exists($cont, $action)) {
        $cont->$action();
    } else {
        die('Acción no encontrada');
    }
} else {
    die('Controlador no encontrado');
}