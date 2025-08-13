<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'models/CategoriasModel.php';

$model = new CategoriasModel();

if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['id'])) {
    $model->eliminarCategoria($_GET['id']);
    header('Location: categorias.php');
    exit();
}

$categorias = $model->obtenerCategorias();

include 'views/categorias_view.php';
