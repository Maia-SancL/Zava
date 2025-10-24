<?php
session_start();
include_once 'c:/xampp/htdocs/Zava/administrador/php/conexion.php';
include_once 'c:/xampp/htdocs/Zava/public/php/mails/gestionCambioCorreo.php';

if (!isset($_SESSION['id'])) {
    header("Location: /Zava/login.php");
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nuevo_correo'])) {
    $id_usuario = $_SESSION['id'];
    $nuevo_correo = $_POST['nuevo_correo'];


    $stmt_check = $conexion->prepare("SELECT id_usuario FROM Usuarios WHERE correo = ?");
    $stmt_check->bind_param("s", $nuevo_correo);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        $mensaje = 'El correo electrónico ingresado ya está en uso por otra cuenta.';
    } else {
        $mensaje = iniciarCambioCorreo($conexion, $id_usuario, $nuevo_correo);
    }
    $stmt_check->close();
} else {
    $mensaje = 'Por favor, proporciona una nueva dirección de correo.';
}


$_SESSION['mensaje_perfil'] = $mensaje;
header("Location: /Zava/comercio/views/perfil.php");
exit;
?>
