<?php
require_once __DIR__ . '/../models/VentasModel.php';

class VentasController {
    private VentasModel $model;

    private float $IVA_RATE = 0.13;

    public function __construct() {
        $this->model = new VentasModel();
    }

    public function listarClientes(): array {
        return $this->model->listarClientes();
    }

    public function listarMetodosPago(): array {
        return $this->model->listarMetodosPago();
    }

    public function registrarVenta(int $clienteCedula, int $metodoPago, array $items): int {
        return $this->model->registrarVenta($clienteCedula, $metodoPago, $items, $this->IVA_RATE);
    }

    
     
    public function generarFacturaTxt(int $ventaId): string {
    $venta = $this->model->obtenerVentaCompleta($ventaId);
    $h = $venta['head'];
    $d = $venta['detalle'];

    $lineas = [];
    $lineas[] = "================= FACTURA =================";
    $lineas[] = "Factura ID:       " . ($h['id_factura'] ?? 'N/A');
    $lineas[] = "Venta ID:         " . $h['id_venta'];
    $lineas[] = "Fecha Emisión:    " . ($h['fecha_emision'] ?? $h['fecha_venta']);
    $lineas[] = "Cliente:          " . $h['nombre'] . ' ' . $h['primer_apellido'] . ' ' . $h['segundo_apellido'];
    $lineas[] = "Cédula / Correo:  " . $h['cedula'] . ' / ' . $h['correo'];
    $lineas[] = "-------------------------------------------";
    $lineas[] = "DETALLE:";
    foreach ($d as $row) {
        $lineas[] = sprintf(
            "- (%d) %s  x%d  @ %.2f  = %.2f",
            $row['id_producto'],
            $row['nombre_producto'],
            $row['cantidad'],
            $row['precio_unitario'],
            $row['subtotal_linea']
        );
    }
    $lineas[] = "-------------------------------------------";
    $lineas[] = sprintf("SUBTOTAL: %.2f", $h['subtotal']);
    $lineas[] = sprintf("IVA:      %.2f", $h['iva']);
    $lineas[] = sprintf("TOTAL:    %.2f", $h['total_factura'] ?? $h['total_venta']);
    $lineas[] = "===========================================";

    $contenido = implode(PHP_EOL, $lineas);

    $dir = __DIR__ . '/../facturas_txt';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }

    $filePath = $dir . '/factura_' . $h['id_venta'] . '.txt';
    file_put_contents($filePath, $contenido);

    return realpath($filePath) ?: $filePath;
}

    
    public function listarVentas(): array {
        $rows = $this->model->listarVentas(); 
        $out  = [];
        foreach ($rows as $r) {
            $out[] = [
                'ID_VENTA'       => (int)$r['id_venta'],
                'NOMBRE_CLIENTE' => $r['cliente'] ?? '',
                'TOTAL'          => (float)$r['total'],
                'FECHA'          => $r['fecha_venta'],
                'TOTAL_ITEMS'    => isset($r['total_items']) ? (int)$r['total_items'] : 0,
            ];
        }
        return $out;
    }

    public function obtenerVenta(int $ventaId): ?array {
        $data = $this->model->obtenerVentaCompleta($ventaId); 
        $h = $data['head'] ?? null;
        if (!$h) return null;

        return [
            'ID_CLIENTE' => $h['cedula'],
            'FECHA'      => $h['fecha_venta'],
            'TOTAL'      => (float)($h['total_factura'] ?? $h['total_venta']),
        ];
    }

    public function listarDetalleVenta(int $ventaId): array {
        $data = $this->model->obtenerVentaCompleta($ventaId);
        $det  = $data['detalle'] ?? [];
        $out  = [];
        foreach ($det as $d) {
            $out[] = [
                'ID_PRODUCTO'     => (int)$d['id_producto'],
                'NOMBRE_PRODUCTO' => $d['nombre_producto'],
                'CANTIDAD'        => (int)$d['cantidad'],
                'PRECIO_UNITARIO' => (float)$d['precio_unitario'],
            ];
        }
        return $out;
    }

    public function listarFacturas(): array {
        $rows = $this->model->listarFacturas();
        $out  = [];
        foreach ($rows as $f) {
            $out[] = [
                'ID_FACTURA' => (int)$f['id_factura'],
                'ID_VENTA'   => (int)$f['id_venta'],
                'CEDULA'     => (int)$f['cedula'],
                'CLIENTE'    => $f['cliente'] ?? '',
                'FECHA'      => $f['fecha_emision'],
                'SUBTOTAL'   => (float)$f['subtotal'],
                'IVA'        => (float)$f['iva'],
                'TOTAL'      => (float)$f['total'],
            ];
        }
        return $out;
    }

    public function obtenerFactura(int $idFactura): array {
        return $this->model->obtenerFactura($idFactura);
    }
}
