<?php
require_once __DIR__ . '/controllers/VentasController.php';

$idVenta = (int)($_GET['id'] ?? 0);
if ($idVenta <= 0) { http_response_code(400); exit('ID de venta inválido'); }

try {
  $vc   = new VentasController();
  $file = $vc->generarFacturaTxt($idVenta);

  if (!is_file($file)) { http_response_code(404); exit('Factura no encontrada.'); }

  header('Content-Type: text/plain; charset=UTF-8');
  header('Content-Length: ' . filesize($file));
  header('Content-Disposition: inline; filename="' . basename($file) . '"');
  header('Cache-Control: no-cache, no-store, must-revalidate');
  header('Pragma: no-cache');
  header('Expires: 0');

  readfile($file);
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo "Error: " . htmlspecialchars($e->getMessage());
}
