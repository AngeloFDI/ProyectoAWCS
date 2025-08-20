<?php

class ComputadorasController {
    public function index() {
        require_once(__DIR__ . '/RecursosController.php');
        $recursosController = new RecursosController();
        $recursosController->computadoras();
    }
}