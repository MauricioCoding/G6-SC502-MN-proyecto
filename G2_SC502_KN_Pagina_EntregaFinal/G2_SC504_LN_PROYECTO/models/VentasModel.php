<?php
require_once __DIR__ . '/../config/conexion.php';

class VentasModel {
    private PDO $db;

    public function __construct() {
        $this->db = getPDOConnection();
    }

    public function listarClientes(): array {
        $sql = "
            SELECT 
                cedula AS CEDULA,
                nombre AS NOMBRE,
                primer_apellido AS PRIMER_APELLIDO,
                segundo_apellido AS SEGUNDO_APELLIDO,
                correo AS CORREO
            FROM FIDE_USUARIOS_TB
            WHERE id_tipo_usuario = 2
              AND id_estado = 1
            ORDER BY nombre, primer_apellido, segundo_apellido
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarMetodosPago(): array {
        $sql = "
            SELECT 
                id_metodo_pago AS ID_METODO_PAGO,
                metodo_pago    AS METODO_PAGO
            FROM FIDE_METODO_PAGO_TB
            WHERE id_estado = 1
            ORDER BY metodo_pago
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function registrarVenta(int $clienteCedula, int $metodoPago, array $items, float $ivaRate = 0.13): int {
        if (empty($items)) {
            throw new Exception("No hay items en el carrito.");
        }

        $subtotal = 0.0;
        foreach ($items as $it) {
            $precio   = (float)$it['precio'];
            $cantidad = (int)$it['cantidad'];
            $subtotal += $precio * $cantidad;
        }
        $iva   = round($subtotal * $ivaRate, 2);
        $total = round($subtotal + $iva, 2);

        try {
            $this->db->beginTransaction();

            $sqlVenta = "
                INSERT INTO FIDE_VENTAS_TB (fecha_venta, total, cedula, id_estado)
                VALUES (NOW(), :total, :cedula, 1)
            ";
            $st = $this->db->prepare($sqlVenta);
            $st->execute([
                ':total'  => $total,
                ':cedula' => $clienteCedula
            ]);
            $ventaId = (int)$this->db->lastInsertId();

            $sqlDet = "
                INSERT INTO FIDE_DETALLE_VENTAS_TB
                    (id_venta, id_producto, cantidad, precio_unitario, id_estado)
                VALUES
                    (:id_venta, :id_producto, :cantidad, :precio_unitario, 1)
            ";
            $std = $this->db->prepare($sqlDet);
            foreach ($items as $it) {
                $std->execute([
                    ':id_venta'        => $ventaId,
                    ':id_producto'     => (int)$it['id'],
                    ':cantidad'        => (int)$it['cantidad'],
                    ':precio_unitario' => (float)$it['precio'],
                ]);
            }

            $sqlFactura = "
                INSERT INTO FIDE_FACTURAS_TB
                    (fecha_emision, subtotal, total, iva, id_venta, cedula, id_estado)
                VALUES
                    (NOW(), :subtotal, :total, :iva, :id_venta, :cedula, 1)
            ";
            $sf = $this->db->prepare($sqlFactura);
            $sf->execute([
                ':subtotal' => $subtotal,
                ':total'    => $total,
                ':iva'      => $iva,
                ':id_venta' => $ventaId,
                ':cedula'   => $clienteCedula
            ]);
            $facturaId = (int)$this->db->lastInsertId();

            $sqlPago = "
                INSERT INTO FIDE_PAGOS_TB
                    (fecha_pago, monto_total, id_metodo_pago, id_factura, id_venta, cedula, id_estado)
                VALUES
                    (NOW(), :monto_total, :id_metodo_pago, :id_factura, :id_venta, :cedula, 1)
            ";
            $sp = $this->db->prepare($sqlPago);
            $sp->execute([
                ':monto_total'    => $total,
                ':id_metodo_pago' => $metodoPago,
                ':id_factura'     => $facturaId,
                ':id_venta'       => $ventaId,
                ':cedula'         => $clienteCedula
            ]);

            $this->db->commit();
            return $ventaId;

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw new Exception("Error registrando la venta: " . $e->getMessage());
        }
    }

    public function obtenerVentaCompleta(int $ventaId): array {
        $sqlHead = "
            SELECT
                v.id_venta,
                v.fecha_venta,
                v.total               AS total_venta,
                u.cedula              AS cedula,
                u.nombre              AS nombre,
                u.primer_apellido     AS primer_apellido,
                u.segundo_apellido    AS segundo_apellido,
                u.correo              AS correo,
                f.id_factura          AS id_factura,
                f.fecha_emision       AS fecha_emision,
                f.subtotal            AS subtotal,
                f.iva                 AS iva,
                f.total               AS total_factura
            FROM FIDE_VENTAS_TB v
            JOIN FIDE_USUARIOS_TB u ON u.cedula = v.cedula
            LEFT JOIN FIDE_FACTURAS_TB f ON f.id_venta = v.id_venta AND f.cedula = v.cedula
            WHERE v.id_venta = :id
        ";
        $st = $this->db->prepare($sqlHead);
        $st->execute([':id' => $ventaId]);
        $head = $st->fetch();
        if (!$head) {
            throw new Exception("Venta no encontrada.");
        }

        $sqlDet = "
            SELECT
                d.id_producto,
                p.nombre        AS nombre_producto,
                d.cantidad,
                d.precio_unitario,
                (d.cantidad * d.precio_unitario) AS subtotal_linea
            FROM FIDE_DETALLE_VENTAS_TB d
            JOIN FIDE_PRODUCTO_TB p ON p.id_producto = d.id_producto
            WHERE d.id_venta = :id
        ";
        $sd = $this->db->prepare($sqlDet);
        $sd->execute([':id' => $ventaId]);
        $detalle = $sd->fetchAll();

        return ['head' => $head, 'detalle' => $detalle];
    }
    public function listarVentas(): array {
        $sql = "
            SELECT 
                v.id_venta,
                v.fecha_venta,
                v.total,
                u.cedula,
                CONCAT(u.nombre, ' ', u.primer_apellido, ' ', u.segundo_apellido) AS cliente,
                COALESCE(SUM(d.cantidad), 0) AS total_items
            FROM FIDE_VENTAS_TB v
            JOIN FIDE_USUARIOS_TB u ON u.cedula = v.cedula
            LEFT JOIN FIDE_DETALLE_VENTAS_TB d ON d.id_venta = v.id_venta
            GROUP BY v.id_venta, v.fecha_venta, v.total, u.cedula, cliente
            ORDER BY v.fecha_venta DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarFacturas(): array {
        $sql = "
            SELECT
                f.id_factura,
                f.id_venta,
                f.cedula,
                f.fecha_emision,
                f.subtotal,
                f.iva,
                f.total,
                CONCAT(u.nombre, ' ', u.primer_apellido, ' ', u.segundo_apellido) AS cliente
        FROM FIDE_FACTURAS_TB f
        JOIN FIDE_USUARIOS_TB u ON u.cedula = f.cedula
        ORDER BY f.fecha_emision DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerFactura(int $idFactura): array {
        $sqlHead = "
            SELECT
                f.id_factura, f.fecha_emision, f.subtotal, f.iva, f.total,
                v.id_venta, v.fecha_venta, v.total AS total_venta,
                u.cedula, u.nombre, u.primer_apellido, u.segundo_apellido, u.correo
            FROM FIDE_FACTURAS_TB f
            JOIN FIDE_VENTAS_TB v ON v.id_venta = f.id_venta AND v.cedula = f.cedula
            JOIN FIDE_USUARIOS_TB u ON u.cedula = f.cedula
            WHERE f.id_factura = :id
            LIMIT 1
        ";
        $st = $this->db->prepare($sqlHead);
        $st->execute([':id' => $idFactura]);
        $head = $st->fetch();
        if (!$head) throw new Exception("Factura no encontrada.");

        $sqlDet = "
            SELECT 
                d.id_producto,
                p.nombre AS nombre_producto,
                d.cantidad,
                d.precio_unitario,
                (d.cantidad * d.precio_unitario) AS subtotal_linea
            FROM FIDE_DETALLE_VENTAS_TB d
            JOIN FIDE_PRODUCTO_TB p ON p.id_producto = d.id_producto
            WHERE d.id_venta = :id_venta
        ";
        $sd = $this->db->prepare($sqlDet);
        $sd->execute([':id_venta' => $head['id_venta']]);
        $detalle = $sd->fetchAll();

        return ['head' => $head, 'detalle' => $detalle];
    }
}
