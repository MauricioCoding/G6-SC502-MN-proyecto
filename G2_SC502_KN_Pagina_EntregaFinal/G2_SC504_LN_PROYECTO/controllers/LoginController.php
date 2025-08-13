<?php
session_start();
require_once '../models/LoginModel.php';

$error = '';

if (!isset($_POST['usuario']) || !isset($_POST['contrasena'])) {
    $error = 'Por favor ingresa usuario y contraseña';
} else {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    if (LoginModel::validarCredenciales($usuario, $contrasena)) {
        $_SESSION['usuario'] = $usuario;

        if (strpos($usuario, 'Admin') !== false) {
            header("Location: ../indexAdmin.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}

include '../login.php';
?>

