<?php
require_once 'models/ProductosModel.php';

$model = new ProductosModel();

if (!isset($_GET['id'])) {
    header('Location: productos.php');
    exit();
}

$id = $_GET['id'];
$producto = $model->obtenerProductoPorId($id);

if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $material = $_POST['material'];
    $peso = $_POST['peso'];
    $estado = $_POST['estado'];

    $model->actualizarProducto($id, $nombre, $descripcion, $precio, $material, $peso, $categoria, $estado);
    header('Location: productos.php');
    exit();
}

$categorias = $model->obtenerCategorias();
$materiales = $model->obtenerMateriales();
$pesos = $model->obtenerPesos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include('views/partials/header.php'); ?>
<?php include('views/partials/navbar.php'); ?>
<div class="container py-5">
    <h2 class="mb-4">Editar Producto</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Descripción:</label>
            <input type="text" class="form-control" name="descripcion" value="<?= htmlspecialchars($producto['descripcion']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Precio:</label>
            <input type="number" step="0.01" class="form-control" name="precio" value="<?= htmlspecialchars($producto['precio_unitario']) ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Categoría:</label>
            <select name="categoria" class="form-select" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= $producto['id_categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Material:</label>
            <select name="material" class="form-select" required>
                <?php foreach ($materiales as $mat): ?>
                    <option value="<?= $mat['id_tipo_material'] ?>" <?= $producto['id_tipo_material'] == $mat['id_tipo_material'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mat['material']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Peso:</label>
            <select name="peso" class="form-select" required>
                <?php foreach ($pesos as $p): ?>
                    <option value="<?= $p['id_peso'] ?>" <?= $producto['id_peso'] == $p['id_peso'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['peso']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Estado:</label>
            <select name="estado" class="form-select" required>
                <option value="1" <?= $producto['id_estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= $producto['id_estado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="productos.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
