<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'controllers/ProductosController.php';

$controller = new ProductosController();

$categorias = $controller->listarCategorias();
$materiales = $controller->obtenerMateriales();
$pesos      = $controller->obtenerPesos();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio      = $_POST['precio'];
    $categoria   = $_POST['categoria'];
    $material    = $_POST['material'];
    $peso        = $_POST['peso'];

    $controller->insertarProducto($nombre, $descripcion, $precio, $categoria, $material, $peso);

    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include('views/partials/header.php'); ?>
<?php include('views/partials/navbar.php'); ?>
<div class="container py-5">
    <h2 class="mb-4">Insertar Nuevo Producto</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>

        <div class="col-md-6">
            <label for="descripcion" class="form-label">Descripción:</label>
            <input type="text" class="form-control" name="descripcion" id="descripcion" required>
        </div>

        <div class="col-md-4">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" step="0.01" class="form-control" name="precio" id="precio" required>
        </div>

        <div class="col-md-4">
            <label for="categoria" class="form-label">Categoría:</label>
            <select name="categoria" id="categoria" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="material" class="form-label">Material:</label>
            <select name="material" id="material" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($materiales as $mat): ?>
                    <option value="<?= $mat['id_tipo_material'] ?>"><?= htmlspecialchars($mat['material']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="peso" class="form-label">Peso:</label>
            <select name="peso" id="peso" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($pesos as $p): ?>
                    <option value="<?= $p['id_peso'] ?>"><?= htmlspecialchars($p['peso']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="productos.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
