<?php
require_once __DIR__ . '/../config/conexion.php';

class ProveedoresModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = getPDOConnection();
    }

  public function listarProveedores()
{
    $sql = "SELECT id_proveedor, nombre_empresa, correo, fecha_registro, id_estado 
            FROM fide_proveedor_tb";
    $stmt = $this->conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function insertarProveedor($empresa, $correo)
{
    $sql = "INSERT INTO fide_proveedor_tb 
            (nombre_empresa, correo, fecha_registro, id_estado) 
            VALUES (:empresa, :correo, CURDATE(), 1)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':empresa', $empresa);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    $ultimoId = $this->conn->lastInsertId();

    return $ultimoId;  
}

public function actualizarProveedor($id, $empresa, $correo)
{
    $sql = "UPDATE fide_proveedor_tb 
            SET nombre_empresa = :empresa, correo = :correo 
            WHERE id_proveedor = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':empresa', $empresa);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
}


public function eliminarProveedor($id)
{
    $sql = "DELETE FROM fide_proveedor_tb WHERE id_proveedor = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
}

public function asociarProducto($idProveedor, $idProducto, $precioCompra)
{
    $stmt = $this->conn->prepare("SELECT 1 FROM fide_proveedor_producto WHERE id_proveedor = :idProveedor AND id_producto = :idProducto");
    $stmt->execute([
        ':idProveedor' => $idProveedor,
        ':idProducto' => $idProducto
    ]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Este producto ya está asociado con este proveedor.');
    }

    $sql = "INSERT INTO fide_proveedor_producto (id_proveedor, id_producto, precio_compra, id_estado) 
            VALUES (:idProveedor, :idProducto, :precioCompra, 1)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':idProveedor' => $idProveedor,
        ':idProducto' => $idProducto,
        ':precioCompra' => $precioCompra
    ]);
}



    
}
