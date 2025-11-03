<?php

/**
 * Reenvía el correo de verificación generando un nuevo token.
 *
 * @param mysqli $conexion La conexión a la base de datos.
 * @param string $token_antiguo El token expirado o inválido.
 * @return string Mensaje de estado sobre el proceso de reenvío.
 */
function reenviarCorreoDeVerificacion($conexion, $token_antiguo) {
    // 1. Buscar al usuario con el token antiguo para obtener su correo y nombre.
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, correo, verificado FROM Usuarios WHERE token_verificacion = ?");
    if (!$stmt) {
        return 'Error al preparar la consulta.';
    }
    $stmt->bind_param("s", $token_antiguo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $stmt->close();
        return 'El token proporcionado no corresponde a ningún usuario. No se puede reenviar el correo.';
    }

    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    // Si la cuenta ya está verificada, no hay nada que hacer.
    if ($usuario['verificado']) {
        return 'Esta cuenta ya ha sido verificada. No es necesario reenviar el correo.';
    }

    // 2. Generar un nuevo token y una nueva fecha de expiración.
    $nuevo_token = bin2hex(random_bytes(16));
    $expiracion = new DateTime();
    $expiracion->add(new DateInterval('PT1H')); // 1 hora de expiración
    $expiracion_str = $expiracion->format('Y-m-d H:i:s');

    // 3. Actualizar el usuario en la base de datos con el nuevo token.
    $stmt_update = $conexion->prepare("UPDATE Usuarios SET token_verificacion = ?, token_expiracion = ? WHERE id_usuario = ?");
    if (!$stmt_update) {
        return 'Error al preparar la actualización.';
    }
    $stmt_update->bind_param("ssi", $nuevo_token, $expiracion_str, $usuario['id_usuario']);
    
    if (!$stmt_update->execute()) {
        $stmt_update->close();
        return 'Hubo un problema al actualizar tu información. Inténtalo de nuevo.';
    }
    $stmt_update->close();

    // 4. Enviar el nuevo correo de verificación.
    // Es necesario incluir la función de envío de correo.
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/mails/enviarVerificacion.php';
    
    $envio_exitoso = enviarCorreoVerificacion($usuario['correo'], $usuario['nombre'], $nuevo_token);

    if ($envio_exitoso === true) {
        return 'Se ha enviado un nuevo correo de verificación a tu dirección. Por favor, revisa tu bandeja de entrada.';
    } else {
        return 'No se pudo enviar el correo de verificación. Por favor, contacta a soporte.';
    }
}
