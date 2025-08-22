<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';

$mensaje = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 1. Buscar el usuario con el token proporcionado.
    $stmt = $conexion->prepare("SELECT id_usuario, nuevo_correo, token_cambio_correo_expiracion FROM Usuarios WHERE token_cambio_correo = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    if ($usuario) {
        $ahora = new DateTime();
        $expiracion = new DateTime($usuario['token_cambio_correo_expiracion']);

        // 2. Verificar si el token ha expirado.
        if ($ahora > $expiracion) {
            $mensaje = 'El enlace de confirmación ha expirado. Por favor, solicita el cambio de correo nuevamente.';
        } else {
            // 3. Actualizar el correo y limpiar los campos del token.
            $nuevo_correo = $usuario['nuevo_correo'];
            $id_usuario = $usuario['id_usuario'];

            $stmt_update = $conexion->prepare("UPDATE Usuarios SET correo = ?, nuevo_correo = NULL, token_cambio_correo = NULL, token_cambio_correo_expiracion = NULL WHERE id_usuario = ?");
            $stmt_update->bind_param("si", $nuevo_correo, $id_usuario);
            
            if ($stmt_update->execute()) {
                $mensaje = '¡Tu dirección de correo electrónico ha sido actualizada con éxito!';
            } else {
                $mensaje = 'Hubo un error al actualizar tu correo. Por favor, inténtalo de nuevo.';
            }
            $stmt_update->close();
        }
    } else {
        $mensaje = 'El enlace de confirmación no es válido o ya ha sido utilizado.';
    }
} else {
    $mensaje = 'No se proporcionó un token de confirmación.';
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Cambio de Correo</title>
    <link rel="stylesheet" href="/Zava/css/general/confirmacion.css">
</head>
<body>
    <div class="container">
        <h1>Confirmación de Cambio de Correo</h1>
        <p><?php echo $mensaje; ?></p>
        <a href="/Zava/php/general/inicioSesion.php" class="btn-login">Ir a Iniciar Sesión</a>
    </div>
</body>
</html>
