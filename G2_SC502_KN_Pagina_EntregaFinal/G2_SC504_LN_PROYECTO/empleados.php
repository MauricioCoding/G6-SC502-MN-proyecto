<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'controllers/EmpleadosController.php';
$controller = new EmpleadosController();

$accion = $_GET['accion'] ?? 'index';

if ($accion === 'registrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $controller->registrarEmpleado($_POST);
    header('Location: empleados.php?ok=' . ($ok ? '1' : '0'));
    exit;
}

if ($accion === 'actualizar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $controller->actualizarEmpleado($_POST);
    header('Location: empleados.php?upd=' . ($ok ? '1' : '0'));
    exit;
}

if ($accion === 'eliminar') {
    $ok = $controller->eliminarEmpleado((int)($_GET['cedula'] ?? 0));
    header('Location: empleados.php?del=' . ($ok ? '1' : '0'));
    exit;
}

if ($accion === 'nuevo') {
    include 'registrar_empleado.php';
    exit;
}

$action = $_GET['accion'] ?? 'consultar';

switch ($action) {
    case 'registrar':
        include 'registrar_empleado.php';
        break;

    case 'actualizar':
        include 'actualizar_empleado.php';
        break;

    case 'eliminar':
        include 'eliminar_empleado.php';
        break;

    case 'consultar':
    default:
        $empleados = $controller->listarEmpleados();
        include 'views/empleados_view.php';
        break;
}
