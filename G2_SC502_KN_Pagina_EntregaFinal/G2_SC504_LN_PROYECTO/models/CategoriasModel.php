<?php
require_once __DIR__ . '/../config/conexion.php';

class CategoriasModel
{
    private $conn;

    public function __construct()
    { {
            $this->conn = getPDOConnection();
        }
    }

    public function obtenerCategorias()
    {
        $sql = "SELECT id_categoria, nombre, descripcion, id_estado FROM FIDE_CATEGORIAS_TB";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarCategoria($nombre, $descripcion)
    {
        $sql = "INSERT INTO FIDE_CATEGORIAS_TB (nombre, descripcion, id_estado) VALUES (:nombre, :descripcion, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
    }

    public function obtenerCategoriaPorId($id)
    {
        $sql = "SELECT id_categoria, nombre, descripcion, id_estado FROM FIDE_CATEGORIAS_TB WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCategoria($id, $nombre, $descripcion, $estado)
    {
        $sql = "UPDATE FIDE_CATEGORIAS_TB SET nombre = :nombre, descripcion = :descripcion, id_estado = :estado WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function eliminarCategoria($id)
    {
        $sqlDeleteProducts = "DELETE FROM fide_producto_tb WHERE id_categoria = :id";
        $stmtProducts = $this->conn->prepare($sqlDeleteProducts);
        $stmtProducts->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtProducts->execute();
        $sql = "DELETE FROM FIDE_CATEGORIAS_TB WHERE id_categoria = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
