<?php
require_once __DIR__ . '/../config/conexion.php';

class InventarioModel {
    private $conn;

    public function __construct() {
        $this->conn = getPDOConnection();
    }

    // Insertar un nuevo movimiento y devolver el ID insertado
    public function insertarMovimiento($id_tipo_movimiento, $id_motivo, $id_estado = 1) {
        $sql = "INSERT INTO FIDE_MOVIMIENTO_INVENTARIO_TB 
                (fecha_movimiento, id_tipo_movimiento, id_motivo, id_estado)
                VALUES (NOW(), :id_tipo, :id_motivo, :id_estado)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_tipo' => $id_tipo_movimiento,
            ':id_motivo' => $id_motivo,
            ':id_estado' => $id_estado
        ]);
        return $this->conn->lastInsertId();
    }

    // Insertar producto asociado a un movimiento
    public function insertarMovimientoProducto($id_movimiento, $id_producto, $cantidad, $precio_unitario, $id_estado = 1) {
        $sql = "INSERT INTO FIDE_MOVIMIENTO_PRODUCTO_TB
                (id_movimiento, id_producto, cantidad, precio_unitario, id_estado)
                VALUES (:id_movimiento, :id_producto, :cantidad, :precio_unitario, :id_estado)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_movimiento' => $id_movimiento,
            ':id_producto' => $id_producto,
            ':cantidad' => $cantidad,
            ':precio_unitario' => $precio_unitario,
            ':id_estado' => $id_estado
        ]);
    }

    public function asociarProducto($id_movimiento, $id_producto, $cantidad)
    {
    $stmt = $this->conn->prepare("SELECT 1 FROM FIDE_MOVIMIENTO_PRODUCTO_TB WHERE id_movimiento = :id_movimiento AND id_producto = :id_producto");
    $stmt->execute([
        ':id_movimiento' => $id_movimiento,
        ':id_producto' => $id_producto
    ]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Este producto ya está asociado al movimiento.');
    }

    $sql = "INSERT INTO FIDE_MOVIMIENTO_PRODUCTO_TB
            (id_movimiento, id_producto, cantidad, precio_unitario, id_estado)
            VALUES (:id_movimiento, :id_producto, :cantidad, 0, 1)"; 
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':id_movimiento' => $id_movimiento,
        ':id_producto' => $id_producto,
        ':cantidad' => $cantidad
    ]);
    }


    // Actualizar movimiento
    public function actualizarMovimiento($id_movimiento, $id_tipo_movimiento, $id_motivo, $id_estado = 1) {
        $sql = "UPDATE FIDE_MOVIMIENTO_INVENTARIO_TB SET
                id_tipo_movimiento = :id_tipo,
                id_motivo = :id_motivo,
                id_estado = :id_estado
                WHERE id_movimiento = :id_movimiento";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_tipo' => $id_tipo_movimiento,
            ':id_motivo' => $id_motivo,
            ':id_estado' => $id_estado,
            ':id_movimiento' => $id_movimiento
        ]);
    }

    // Eliminar movimiento y sus productos asociados
    public function eliminarMovimiento($id_movimiento) {
        // Transacción para asegurar integridad
        $this->conn->beginTransaction();

        try {
            $stmt1 = $this->conn->prepare("DELETE FROM FIDE_MOVIMIENTO_PRODUCTO_TB WHERE id_movimiento = :id_movimiento");
            $stmt1->execute([':id_movimiento' => $id_movimiento]);

            $stmt2 = $this->conn->prepare("DELETE FROM FIDE_MOVIMIENTO_INVENTARIO_TB WHERE id_movimiento = :id_movimiento");
            $stmt2->execute([':id_movimiento' => $id_movimiento]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Listar movimientos con filtros opcionales
  public function obtenerMovimientos($tipo = null, $fechaInicio = null, $fechaFin = null, $categoria = null) {
    $sql = "
        SELECT
            mi.id_movimiento,
            mi.fecha_movimiento,
            tm.tipo AS tipo_movimiento,
            mm.motivo,
            mi.id_estado
        FROM FIDE_MOVIMIENTO_INVENTARIO_TB mi
        INNER JOIN FIDE_TIPO_MOVIMIENTO_TB tm ON mi.id_tipo_movimiento = tm.id_tipo_movimiento
        INNER JOIN FIDE_MOTIVO_MOVIMIENTO_TB mm ON mi.id_motivo = mm.id_motivo
        WHERE 1=1
    ";

    $params = [];

    if ($tipo !== null) {
        $sql .= " AND tm.tipo = :tipo";
        $params[':tipo'] = $tipo;
    }
    if ($fechaInicio !== null) {
        $sql .= " AND mi.fecha_movimiento >= :fechaInicio";
        $params[':fechaInicio'] = $fechaInicio . " 00:00:00";
    }
    if ($fechaFin !== null) {
        $sql .= " AND mi.fecha_movimiento <= :fechaFin";
        $params[':fechaFin'] = $fechaFin . " 23:59:59";
    }

    // If you want to filter by category, you need to join products
    if ($categoria !== null) {
        $sql .= " AND EXISTS (
            SELECT 1
            FROM FIDE_MOVIMIENTO_PRODUCTO_TB mp
            INNER JOIN FIDE_PRODUCTO_TB p ON mp.id_producto = p.id_producto
            WHERE mp.id_movimiento = mi.id_movimiento
              AND p.id_categoria = :categoria
        )";
        $params[':categoria'] = $categoria;
    }

    $sql .= " ORDER BY mi.fecha_movimiento DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function obtenerMotivos() {
    $stmt = $this->conn->prepare("SELECT id_motivo, motivo FROM FIDE_MOTIVO_MOVIMIENTO_TB WHERE id_estado = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerTiposMovimiento() {
    $stmt = $this->conn->prepare("SELECT id_tipo_movimiento, tipo FROM FIDE_TIPO_MOVIMIENTO_TB WHERE id_estado = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
