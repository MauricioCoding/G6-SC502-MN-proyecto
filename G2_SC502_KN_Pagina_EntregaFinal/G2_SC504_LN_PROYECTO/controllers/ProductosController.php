<?php
require_once __DIR__ . '/../models/ProductosModel.php';

class ProductosController
{
    private $model;

    public function __construct()   
    {
        $this->model = new ProductosModel();
    }

    public function listarProductos($id_categoria = null)
    {
        return $this->model->obtenerProductos($id_categoria);
    }

    public function listarCategorias()
    {
        return $this->model->obtenerCategorias();
    }

    public function eliminarProducto($id)
    {
        return $this->model->eliminarProducto($id);
    }

    public function insertarProducto($nombre, $descripcion, $precio, $categoria, $material, $peso)
    {
        return $this->model->insertarProducto($nombre, $descripcion, $precio, $categoria, $material, $peso);
    }

    public function obtenerMateriales()
    {
        return $this->model->obtenerMateriales();
    }

    public function obtenerPesos()
    {
        return $this->model->obtenerPesos();
    }
}
