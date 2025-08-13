<?php
require_once 'controllers/EmpleadosController.php';
include('views/partials/header.php');
include('views/partials/navbar.php');

$controller = new EmpleadosController();
$puestos = $controller->listarPuestos();
$roles   = $controller->listarRoles();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<section class="section-box rounded-4 shadow-sm my-4">
  <div class="container py-4">

    <div class="form-card rounded-4 p-3 p-md-4">
      <h2 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i>Registrar Nuevo Empleado</h2>

      <form action="empleados.php?accion=registrar" method="POST">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Cédula</label>
            <input type="number" name="cedula" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Primer Apellido</label>
            <input type="text" name="apellido1" class="form-control rounded-pill" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Segundo Apellido</label>
            <input type="text" name="apellido2" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Usuario</label>
            <input type="text" name="usuario" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="pass" class="form-control rounded-pill" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Correo</label>
            <input type="email" name="correo" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Fecha de nacimiento</label>
            <input type="date" name="fecha" class="form-control rounded-pill" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Salario</label>
            <input type="number" name="salario" class="form-control rounded-pill" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Puesto</label>
            <select name="puesto" class="form-select rounded-pill" required>
              <option value="" disabled selected hidden>Seleccione</option>
              <?php foreach ($puestos as $p): ?>
                <option value="<?= $p['id_puesto'] ?>"><?= htmlspecialchars($p['nombre_puesto']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Rol</label>
            <select name="rol" class="form-select rounded-pill" required>
              <option value="" disabled selected hidden>Seleccione</option>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id_rol'] ?>"><?= htmlspecialchars($r['rol']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="submit" class="btn btn-accent rounded-pill">
            <i class="bi bi-check2-circle me-1"></i> Registrar
          </button>
          <a href="empleados.php" class="btn btn-outline-secondary rounded-pill">
            Cancelar
          </a>
        </div>
      </form>
    </div>

  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
