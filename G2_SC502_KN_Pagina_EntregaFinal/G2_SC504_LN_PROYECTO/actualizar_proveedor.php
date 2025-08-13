<?php
require_once 'controllers/ProveedoresController.php';
include('views/partials/header.php'); 
include('views/partials/navbar.php');

$controller = new ProveedoresController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id_proveedor'];
    $empresa = $_POST['empresa'];
    $correo  = $_POST['correo'];
    $controller->actualizarProveedor($id, $empresa, $correo);
    echo "<section class='section-box rounded-4 shadow-sm my-4'><div class='container py-3'>
            <div class='alert alert-warning text-center rounded-4 m-0'>Proveedor actualizado correctamente.</div>
          </div></section>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Actualizar Proveedor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css?v=5">
</head>
<body>

<section class="section-box rounded-4 shadow-sm my-4">
  <div class="container py-4">

    <div class="form-card rounded-4 p-3 p-md-4">
      <h2 class="fw-bold mb-4"><i class="bi bi-building-gear me-2"></i> Actualizar Proveedor</h2>

      <form method="POST" action="actualizar_proveedor.php">
        <div class="mb-3">
          <label for="id_proveedor" class="form-label fw-semibold">ID Proveedor</label>
          <input type="number" class="form-control rounded-pill" id="id_proveedor" name="id_proveedor" required>
        </div>

        <div class="mb-3">
          <label for="empresa" class="form-label fw-semibold">Nuevo Nombre de Empresa</label>
          <input type="text" class="form-control rounded-pill" id="empresa" name="empresa" required>
        </div>

        <div class="mb-3">
          <label for="correo" class="form-label fw-semibold">Nuevo Correo Electrónico</label>
          <input type="email" class="form-control rounded-pill" id="correo" name="correo" required>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-accent rounded-pill">
            <i class="bi bi-check2-circle me-1"></i> Actualizar
          </button>
          <a href="proveedores.php" class="btn btn-outline-secondary rounded-pill">Cancelar</a>
        </div>
      </form>
    </div>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
