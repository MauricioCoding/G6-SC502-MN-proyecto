<?php
require_once __DIR__ . '/../models/InventarioModel.php';

class InventarioController {
    private $model;

    public function __construct() {
        $this->model = new InventarioModel();
    }

    public function mostrarReporte() {
        $tipos = $this->obtenerTiposMovimiento();
        $motivos = $this->obtenerMotivos();

        include __DIR__ . '/../views/inventario_view.php';
    }

        public function mostrarFormularioActualizar() {
        $tipos = $this->model->obtenerTiposMovimiento();
        $motivos = $this->model->obtenerMotivos();
 
        include __DIR__ . '/../actualizar_movimiento.php';
    }

    public function insertarMovimiento($id_tipo, $id_motivo) {
        return $this->model->insertarMovimiento($id_tipo, $id_motivo);
    }

    public function insertarMovimientoProducto($id_movimiento, $id_producto, $cantidad, $precio_unitario) {
        return $this->model->insertarMovimientoProducto($id_movimiento, $id_producto, $cantidad, $precio_unitario);
    }

    public function asociarProducto($id_movimiento, $id_producto, $cantidad) {
    return $this->model->asociarProducto($id_movimiento, $id_producto, $cantidad);
}


    public function actualizarMovimiento($id_movimiento, $id_tipo, $id_motivo) {
        return $this->model->actualizarMovimiento($id_movimiento, $id_tipo, $id_motivo);
    }

    public function eliminarMovimiento($id_movimiento) {
        return $this->model->eliminarMovimiento($id_movimiento);
    }

    public function listarMovimientos($tipo = null, $fechaInicio = null, $fechaFin = null, $categoria = null) {
        return $this->model->obtenerMovimientos($tipo, $fechaInicio, $fechaFin, $categoria);
    }

    public function obtenerMotivos() {
        return $this->model->obtenerMotivos();
    }

    public function obtenerTiposMovimiento() {
        return $this->model->obtenerTiposMovimiento();
    }
}

