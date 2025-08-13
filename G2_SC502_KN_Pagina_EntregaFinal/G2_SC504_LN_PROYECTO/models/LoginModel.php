<?php
require_once __DIR__ . '/../config/conexion.php';
class LoginModel {
    public static function validarCredenciales($usuario, $contrasena) {
        $conn = getPDOConnection();

        $sql = "SELECT COUNT(*) FROM FIDE_USUARIOS_TB WHERE nombre_usuario = :usuario AND contrasea = :contrasena";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);

        $stmt->execute();

        $existe = $stmt->fetchColumn();

        return $existe > 0;
    }
}
?>

