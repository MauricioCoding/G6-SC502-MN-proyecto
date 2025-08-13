<?php include 'partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Productos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css?v=2">
</head>

<body class="bg-light">

  <section class="section-box rounded-4 shadow-sm my-4">
    <div class="container py-4">

      <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="m-0 fw-bold">Piedras &amp; Enchapes</h2>
        <a href="insertar_producto.php" class="btn btn-accent rounded-pill">
          <i class="bi bi-plus-lg"></i> Insertar Producto
        </a>
      </div>

      <form method="GET" action="productos.php" class="filter-bar rounded-4 p-3 mb-4">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-auto">
            <label class="form-label m-0 me-2 fw-semibold">Categoría</label>
          </div>
          <div class="col-12 col-md-auto">
            <select name="categoria" class="form-select rounded-pill" onchange="this.form.submit()">
              <option value="">-- Todas las categorías --</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>"
                  <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </form>

      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (!empty($productos)): ?>
          <?php foreach ($productos as $prod): ?>
            <div class="col">
              <div class="card product-card h-100 rounded-4 shadow-sm">
                <div class="ratio ratio-16x9 product-thumb">
                  <img src="./assets/img/sample-img.jpg" class="card-img-top rounded-top-4" alt="Imagen producto">
                </div>

                <div class="card-body">
                  <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($prod['nombre']) ?></h5>
                  <p class="card-text text-muted mb-2"><?= htmlspecialchars($prod['descripcion']) ?></p>
                  <p class="fw-bold m-0">₡<?= number_format($prod['precio_unitario'], 0, ',', '.') ?></p>
                </div>

                <div class="card-footer bg-transparent border-0 d-flex flex-wrap gap-2 justify-content-between align-items-center">

                  <div class="d-flex gap-2">
                    <a href="editar_producto.php?id=<?= $prod['id_producto'] ?>" class="btn btn-outline-secondary btn-sm rounded-pill" title="Editar">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="productos.php?action=eliminar&id=<?= $prod['id_producto'] ?>"
                       class="btn btn-outline-danger btn-sm rounded-pill"
                       onclick="return confirm('¿Seguro que desea eliminar este producto?')"
                       title="Eliminar">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>


                  <form method="post" action="carrito.php?action=add" class="d-flex align-items-center gap-2 ms-auto">
                    <input type="hidden" name="id" value="<?= $prod['id_producto'] ?>">
                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($prod['nombre']) ?>">
                    <input type="hidden" name="precio" value="<?= $prod['precio_unitario'] ?>">
                    <input type="number" name="cantidad" value="1" min="1"
                           class="form-control form-control-sm rounded-pill"
                           style="width: 90px;">
                    <button class="btn btn-outline-accent btn-sm rounded-pill">
                      <i class="bi bi-cart-plus"></i> Añadir
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col">
            <div class="alert alert-info text-center rounded-4">No hay productos registrados.</div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
