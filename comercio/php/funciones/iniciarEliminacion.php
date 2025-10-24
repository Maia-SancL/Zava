<?php
session_start();
include_once 'c:/xampp/htdocs/Zava/administrador/php/conexion.php';
include_once 'c:/xampp/htdocs/Zava/public/php/mails/gestionEliminacion.php';

if (!isset($_SESSION['id'])) {
    header("Location: /Zava/login.php");
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id'];
    $mensaje = iniciarEliminacionCuenta($conexion, $id_usuario);
} else {
    $mensaje = 'Solicitud no válida.';
}

$_SESSION['mensaje_perfil'] = $mensaje;
header("Location: /Zava/comercio/views/perfil.php");
exit;
?>
