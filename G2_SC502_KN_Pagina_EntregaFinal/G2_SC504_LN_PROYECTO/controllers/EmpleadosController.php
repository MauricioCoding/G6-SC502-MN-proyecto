<?php
require_once __DIR__ . '/../models/EmpleadosModel.php';

class EmpleadosController
{
    private $model;

    public function __construct()
    {
        $this->model = new EmpleadosModel();
    }

    public function index()
    {
        $empleados = $this->model->listarEmpleados(1);
        include 'views/empleados_view.php';
    }

    public function listarEmpleados()
    {
        return $this->model->listarEmpleados(1);
    }

    public function registrarEmpleado($datos)
    {
        $data = [
            'cedula'            => (int)($_POST['cedula'] ?? 0),
            'nombre'            => trim($_POST['nombre'] ?? ''),
            'primer_apellido'   => trim($_POST['apellido1'] ?? ''),
            'segundo_apellido'  => trim($_POST['apellido2'] ?? ''),
            'nombre_usuario'    => trim($_POST['usuario'] ?? ''),
            'contrasena'        => $_POST['pass'] ?? '',
            'correo'            => trim($_POST['correo'] ?? ''),
            'fecha_nacimiento'  => $_POST['fecha'] ?? '', // YYYY-MM-DD
            'salario'           => (float)($_POST['salario'] ?? 0),
            'id_puesto_usuario' => (int)($_POST['puesto'] ?? 0),
            'id_rol_usuario'    => (int)($_POST['rol'] ?? 0),
            'id_estado'         => (int)($_POST['id_estado'] ?? 1),
        ];
        return $this->model->insertarEmpleado($data);
    }


    public function actualizarEmpleado($datos)
    {
        $data = [
            'cedula'            => (int)($_POST['cedula'] ?? 0),
            'nombre'            => trim($_POST['nombre'] ?? ''),
            'primer_apellido'   => trim($_POST['apellido1'] ?? ''),
            'segundo_apellido'  => trim($_POST['apellido2'] ?? ''),
            'nombre_usuario'    => trim($_POST['usuario'] ?? ''),
            'contrasena'        => $_POST['pass'] ?? '', 
            'correo'            => trim($_POST['correo'] ?? ''),
            'fecha_nacimiento'  => $_POST['fecha'] ?? '',
            'salario'           => (float)($_POST['salario'] ?? 0),
            'id_puesto_usuario' => (int)($_POST['puesto'] ?? 0),
            'id_rol_usuario'    => (int)($_POST['rol'] ?? 0),
            'id_estado'         => (int)($_POST['id_estado'] ?? 1),
        ];
        return $this->model->actualizarEmpleado($data);
    }



    public function eliminarEmpleado($cedula)
    {
        return $this->model->eliminarEmpleado($cedula);
    }

    public function obtenerEmpleado($cedula)
    {
        return $this->model->obtenerEmpleadoPorCedula($cedula);
    }

    public function listarPuestos()
    {
        return $this->model->listarPuestos();
    }

    public function listarRoles()
    {
        return $this->model->listarRoles();
    }
}
