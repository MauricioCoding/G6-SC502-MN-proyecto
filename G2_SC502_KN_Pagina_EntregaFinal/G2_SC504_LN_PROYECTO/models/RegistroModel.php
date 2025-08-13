<?php
require_once __DIR__ . '/../config/conexion.php';

class RegistroModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = getPDOConnection();
    }
    public function insertarUsuarioCliente($datos)
    {
        $sql = "INSERT INTO FIDE_USUARIOS_TB (
                    CEDULA, NOMBRE, primer_apellido, segundo_apellido,
                    nombre_usuario, contrasea, CORREO, FECHA_NACIMIENTO,
                    fecha_registro
                ) VALUES (
                    :cedula, :nombre, :primer_apellido, :segundo_apellido,
                    :nombre_usuario, :contrasea, :correo, :fecha_nacimiento,
                    NOW()
                )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':cedula', $datos['cedula']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':primer_apellido', $datos['primer_apellido']);
        $stmt->bindParam(':segundo_apellido', $datos['segundo_apellido']);
        $stmt->bindParam(':nombre_usuario', $datos['nombre_usuario']);
        $stmt->bindParam(':contrasea', $datos['contrasena']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);

        return $stmt->execute();
    }
}
?>