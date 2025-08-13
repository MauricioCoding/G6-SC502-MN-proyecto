<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/ProveedoresController.php';

$controller = new ProveedoresController();

$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);

$response = ['success' => false, 'message' => 'Acción no definida'];

switch ($method) {
    case 'GET':
        $proveedores = $controller->listarProveedores();
        echo json_encode($proveedores);
        break;

    case 'POST':
        if (isset($input['empresa'], $input['correo'])) {
            $controller->registrarProveedor($input['empresa'], $input['correo']);
            $response = ['success' => true, 'message' => 'Proveedor registrado'];
        } else {
            $response = ['success' => false, 'message' => 'Datos incompletos'];
        }
        echo json_encode($response);
        break;

    case 'PUT':
        if (isset($input['id_proveedor'], $input['empresa'], $input['correo'])) {
            $controller->actualizarProveedor($input['id_proveedor'], $input['empresa'], $input['correo']);
            $response = ['success' => true, 'message' => 'Proveedor actualizado'];
        } else {
            $response = ['success' => false, 'message' => 'Datos incompletos'];
        }
        echo json_encode($response);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $delete_vars);
        if (isset($delete_vars['id_proveedor'])) {
            $controller->eliminarProveedor($delete_vars['id_proveedor']);
            $response = ['success' => true, 'message' => 'Proveedor eliminado'];
        } else {
            $response = ['success' => false, 'message' => 'ID no proporcionado'];
        }
        echo json_encode($response);
        break;

    default:
        echo json_encode($response);
}