<?php
require_once 'controllers/EmpleadosController.php';
include('views/partials/header.php');
include('views/partials/navbar.php');

$controller = new EmpleadosController();

if (!isset($_GET['cedula'])) {
  header("Location: empleados.php");
  exit;
}

$cedula   = $_GET['cedula'];
$empleado = $controller->obtenerEmpleado($cedula);
$puestos  = $controller->listarPuestos();
$roles    = $controller->listarRoles();
$valorFecha = $empleado['fecha_nacimiento'] ?? '';


?>

<section class="section-box rounded-4 shadow-sm my-4">
  <div class="container py-4">

    <div class="form-card rounded-4 p-3 p-md-4">
      <h2 class="fw-bold mb-4">
        <i class="bi bi-person-gear me-2"></i> Actualizar Empleado
      </h2>

      <form action="empleados.php?accion=actualizar" method="POST">
        <input type="hidden" name="cedula" value="<?= htmlspecialchars($empleado['cedula']) ?>">

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['nombre']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Primer Apellido</label>
            <input type="text" name="apellido1" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['primer_apellido']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Segundo Apellido</label>
            <input type="text" name="apellido2" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['segundo_apellido']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Usuario</label>
            <input type="text" name="usuario" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['nombre_usuario']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="pass" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['contrasea']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Correo</label>
            <input type="email" name="correo" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['correo']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Fecha de nacimiento</label>
            <input type="date" name="fecha" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($valorFecha) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Salario</label>
            <input type="number" name="salario" class="form-control rounded-pill"
                   value="<?= htmlspecialchars($empleado['salario']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Puesto</label>
            <select name="puesto" class="form-select rounded-pill" required>
              <?php foreach ($puestos as $p): ?>
                <option value="<?= $p['id_puesto'] ?>"
                  <?= $p['id_puesto'] == $empleado['id_puesto_usuario'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($p['nombre_puesto']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Rol</label>
            <select name="rol" class="form-select rounded-pill" required>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id_rol'] ?>"
                  <?= $r['id_rol'] == $empleado['id_rol_usuario'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($r['rol']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Estado</label>
            <select name="estado" class="form-select rounded-pill" required>
              <option value="1" <?= ($empleado['id_estado'] == 1 ? 'selected' : '') ?>>ACTIVO</option>
              <option value="2" <?= ($empleado['id_estado'] == 2 ? 'selected' : '') ?>>INACTIVO</option>
            </select>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="submit" class="btn btn-accent rounded-pill">
            <i class="bi bi-check2-circle me-1"></i> Actualizar
          </button>
          <a href="empleados.php" class="btn btn-outline-secondary rounded-pill">Cancelar</a>
        </div>
      </form>
    </div>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
