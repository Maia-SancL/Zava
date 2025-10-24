<?php

/**
 * Verifica un token de verificación de cuenta y actualiza el estado del usuario.
 *
 * @param mysqli $conexion La conexión a la base de datos.
 * @param string $token El token a verificar.
 * @return string Un mensaje indicando el resultado del proceso.
 */
function verificarTokenDeCuenta($conexion, $token) {
    // 1. Buscar el usuario por el token de forma segura.
    $stmt = $conexion->prepare("SELECT id_usuario, verificado, token_expiracion FROM Usuarios WHERE token_verificacion = ?");
    if (!$stmt) {
        return 'Error preparando la consulta: ' . $conexion->error;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // 2. Comprobar si el token existe.
    if ($resultado->num_rows === 0) {
        $stmt->close();
        return 'Token de verificación inválido.';
    }

    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    // 3. Comprobar si la cuenta ya está verificada.
    if ($usuario['verificado']) {
        return 'Esta cuenta ya ha sido verificada. Puedes <a href="inicioSesion.php">iniciar sesión</a>.';
    }

    // 4. Comprobar si el token ha expirado.
    if (strtotime($usuario['token_expiracion']) < time()) {
        $reenvio_link = "solicitarReenvio.php?token={$token}";
        return "El enlace de verificación ha expirado. <a href='{$reenvio_link}'>Haz clic aquí para reenviar el correo</a>.";
    }

    // 5. Actualizar el estado del usuario a verificado.
    $id_usuario = $usuario['id_usuario'];
    $stmt_update = $conexion->prepare("UPDATE Usuarios SET verificado = TRUE, token_verificacion = NULL, token_expiracion = NULL WHERE id_usuario = ?");
    if (!$stmt_update) {
        return 'Error preparando la actualización: ' . $conexion->error;
    }
    $stmt_update->bind_param("i", $id_usuario);
    
    if ($stmt_update->execute()) {
        $stmt_update->close();
        return 'success';
    } else {
        $error = $stmt_update->error;
        $stmt_update->close();
        return 'Error al verificar la cuenta. Por favor, inténtalo de nuevo más tarde. (' . $error . ')';
    }
}
