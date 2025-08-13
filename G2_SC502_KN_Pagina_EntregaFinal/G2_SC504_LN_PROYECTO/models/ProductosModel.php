<?php
require_once __DIR__ . '/../config/conexion.php';

class ProductosModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = getPDOConnection(); 
    }

public function obtenerProductos($id_categoria = null)
{
    if ($id_categoria) {
        $stmt = $this->conn->prepare("SELECT p.*, tm.material, pp.peso, c.nombre, e.estado
                                      FROM FIDE_PRODUCTO_TB p
                                      LEFT JOIN FIDE_TIPO_MATERIAL_TB tm ON p.id_tipo_material = tm.id_tipo_material
                                      LEFT JOIN FIDE_PESO_PRODUCTOS_TB pp ON p.id_peso = pp.id_peso
                                      LEFT JOIN FIDE_CATEGORIAS_TB c ON p.id_categoria = c.id_categoria
                                      LEFT JOIN FIDE_ESTADOS_TB e ON p.id_estado = e.id_estado
                                      WHERE p.id_categoria = :categoria");
        $stmt->bindParam(':categoria', $id_categoria, PDO::PARAM_INT);
    } else {
        $stmt = $this->conn->prepare("SELECT p.*, tm.material, pp.peso, c.nombre, e.estado
                                      FROM FIDE_PRODUCTO_TB p
                                      LEFT JOIN FIDE_TIPO_MATERIAL_TB tm ON p.id_tipo_material = tm.id_tipo_material
                                      LEFT JOIN FIDE_PESO_PRODUCTOS_TB pp ON p.id_peso = pp.id_peso
                                      LEFT JOIN FIDE_CATEGORIAS_TB c ON p.id_categoria = c.id_categoria
                                      LEFT JOIN FIDE_ESTADOS_TB e ON p.id_estado = e.id_estado");
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerCategorias()
    {
        $stmt = $this->conn->prepare("SELECT * FROM FIDE_CATEGORIAS_TB");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMateriales()
    {
        $stmt = $this->conn->prepare("SELECT * FROM FIDE_TIPO_MATERIAL_TB");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPesos()
    {
        $stmt = $this->conn->prepare("SELECT * FROM FIDE_PESO_PRODUCTOS_TB");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarProducto($nombre, $descripcion, $precio, $categoria, $material, $peso)
    {
        $stmt = $this->conn->prepare("INSERT INTO FIDE_PRODUCTO_TB 
            (nombre, descripcion, precio_unitario, id_categoria, id_tipo_material, id_peso, id_estado) 
            VALUES (:nombre, :descripcion, :precio, :categoria, :material, :peso, 1)"); // assuming default estado = 1 (active)

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':material', $material, PDO::PARAM_INT);
        $stmt->bindParam(':peso', $peso, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function eliminarProducto($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM FIDE_PRODUCTO_TB WHERE id_producto = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerProductoPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT p.*, tm.material, pp.peso, c.nombre AS categoria, e.estado
                                      FROM FIDE_PRODUCTO_TB p
                                      LEFT JOIN FIDE_TIPO_MATERIAL_TB tm ON p.id_tipo_material = tm.id_tipo_material
                                      LEFT JOIN FIDE_PESO_PRODUCTOS_TB pp ON p.id_peso = pp.id_peso
                                      LEFT JOIN FIDE_CATEGORIAS_TB c ON p.id_categoria = c.id_categoria
                                      LEFT JOIN FIDE_ESTADOS_TB e ON p.id_estado = e.id_estado
                                      WHERE p.id_producto = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarProducto($id, $nombre, $descripcion, $precio, $material, $peso, $categoria, $estado)
    {
        $stmt = $this->conn->prepare("UPDATE FIDE_PRODUCTO_TB SET
            nombre = :nombre,
            descripcion = :descripcion,
            precio_unitario = :precio,
            id_tipo_material = :material,
            id_peso = :peso,
            id_categoria = :categoria,
            id_estado = :estado
            WHERE id_producto = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':material', $material, PDO::PARAM_INT);
        $stmt->bindParam(':peso', $peso, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
