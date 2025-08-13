<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top my-navbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="assets/img/logo.png" alt="Logo" height="70">
      <span class="fw-bold text-uppercase">Piedras & Enchapes</span>
    </a>
    <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        <li class="nav-item">
          <a class="nav-link px-3 rounded-pill <?= basename($_SERVER['PHP_SELF'])=='index.php' ? 'active fw-bold' : '' ?>" href="index.php">
            <i class="bi bi-house-door"></i> Inicio 
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link px-3 rounded-pill <?= basename($_SERVER['PHP_SELF'])=='login.php' ? 'active fw-bold' : '' ?>" href="login.php">
            <i class="bi bi-box-arrow-in-right"></i> Login 
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3 rounded-pill <?= in_array(basename($_SERVER['PHP_SELF']), ['productos.php','categorias.php']) ? 'active fw-bold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-gem"></i> Piedras & Enchapes
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><a class="dropdown-item" href="productos.php"><i class="bi bi-box-seam"></i> Productos</a></li>
            <li><a class="dropdown-item" href="categorias.php"><i class="bi bi-tags"></i> Categorías</a></li>
          </ul>
        </li>
 
        <li class="nav-item">
          <a class="nav-link px-3 rounded-pill <?= basename($_SERVER['PHP_SELF']) == 'carrito.php' ? 'active fw-bold' : '' ?>" href="carrito.php">
            <i class="bi bi-cart"></i> Carrito
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link px-3 rounded-pill <?= basename($_SERVER['PHP_SELF']) == 'ventas.php' ? 'active fw-bold' : '' ?>" href="ventas.php">
            <i class="bi bi-receipt"></i> Ventas
          </a>
        </li>

        <?php if (isset($_SESSION['usuario'])): ?>
          <li class="nav-item">
            <span class="navbar-text me-3">Hola, <?= $_SESSION['usuario'] ?></span>
          </li>
          <li class="nav-item">
            <a class="btn btn-sm btn-outline-danger" href="controllers/LogoutController.php">Cerrar sesión</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>