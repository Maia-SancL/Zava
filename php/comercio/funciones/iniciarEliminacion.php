<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/mails/gestionEliminacion.php';

if (!isset($_SESSION['id'])) {
    header("Location: /Zava/php/general/inicioSesion.php");
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
header("Location: /Zava/php/comercio/perfil/perfil.php");
exit;
?>
