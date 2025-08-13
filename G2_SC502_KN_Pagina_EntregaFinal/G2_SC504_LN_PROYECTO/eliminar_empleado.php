<?php
require_once 'controllers/EmpleadosController.php';

if (!isset($_GET['cedula'])) {
    header('Location: empleados.php');
    exit;
}

$cedula = (int) $_GET['cedula'];

$controller = new EmpleadosController();
$ok = $controller->eliminarEmpleado($cedula);

header('Location: empleados.php?del=' . ($ok ? '1' : '0'));
exit;
