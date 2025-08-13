<?php   
require_once __DIR__ . '/../models/ProveedoresModel.php';

class ProveedoresController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProveedoresModel();
    }

    public function index()
    {
        $proveedores = $this->model->listarProveedores();
        include __DIR__ . '/../views/proveedores_view.php';
    }

    public function registrarProveedor($empresa, $correo)
    {
        $this->model->insertarProveedor($empresa, $correo);
    }

    public function actualizarProveedor($id_proveedor, $empresa, $correo)
    {
        $this->model->actualizarProveedor($id_proveedor, $empresa, $correo);
    }

    public function eliminarProveedor($id_proveedor)
    {
        $this->model->eliminarProveedor($id_proveedor);
    }

    public function asociarProductoProveedor($id_proveedor, $id_producto, $precio_compra)
    {
        try {
        $this->model->asociarProducto($id_proveedor, $id_producto, $precio_compra);
        header('Location: proveedores.php?success=1');
        }  catch (Exception $e) {
        header('Location: proveedores.php?error=' . urlencode($e->getMessage()));
        }
    exit;
    }


    public function listarProveedores()
    {
        return $this->model->listarProveedores();
    }
}
