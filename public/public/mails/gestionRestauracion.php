<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/Exception.php';

/**
 * Inicia el proceso de restauración de contraseña.
 *
 * @param mysqli $conexion La conexión a la base de datos.
 * @param string $correo El correo del usuario que solicita la restauración.
 * @return string Mensaje de estado.
 */
function solicitarRestauracionContrasenia($conexion, $correo) {
    // 1. Buscar al usuario por correo.
    $stmt = $conexion->prepare("SELECT id_usuario, nombre FROM Usuarios WHERE correo = ?");
    if (!$stmt) return 'Error al preparar la consulta.';
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        // No revelamos si el correo existe o no por seguridad.
        return 'Si tu correo está registrado, recibirás un enlace.';
    }

    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    // 2. Generar token y fecha de expiración.
    $token = bin2hex(random_bytes(16));
    $expiracion = new DateTime();
    $expiracion->add(new DateInterval('PT1H')); // 1 hora de validez
    $expiracion_str = $expiracion->format('Y-m-d H:i:s');

    // 3. Guardar el token en la base de datos.
    $stmt_update = $conexion->prepare("UPDATE Usuarios SET token_restauracion = ?, token_restauracion_expiracion = ? WHERE id_usuario = ?");
    if (!$stmt_update) return 'Error al preparar la actualización.';
    $stmt_update->bind_param("ssi", $token, $expiracion_str, $usuario['id_usuario']);
    
    if (!$stmt_update->execute()) {
        $stmt_update->close();
        return 'Hubo un problema al iniciar la restauración. Inténtalo de nuevo.';
    }
    $stmt_update->close();

    // 4. Enviar el correo de restauración.
    return enviarCorreoRestauracion($correo, $usuario['nombre'], $token);
}

/**
 * Envía el correo con el enlace de restauración.
 *
 * @param string $correo
 * @param string $nombre
 * @param string $token
 * @return string Mensaje de estado.
 */
function enviarCorreoRestauracion($correo, $nombre, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Zava.NoReply@gmail.com';
        $mail->Password   = 'gexfupvuftkmpdre';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('Zava.NoReply@gmail.com', 'Equipo de Zava');
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Restauracion de Contrasena - Zava';
        $restauracion_link = "http://" . $_SERVER['HTTP_HOST'] . "/Zava/php/general/restaurarContrasenia.php?token={$token}";
        $mail->Body    = "Has solicitado restaurar tu contraseña. Haz clic en el siguiente enlace para continuar: <a href='{$restauracion_link}'>Restaurar Contraseña</a>. Si no solicitaste esto, ignora este mensaje.";
        $mail->AltBody = "Usa el siguiente enlace para restaurar tu contraseña: {$restauracion_link}";

        $mail->send();
        return 'Si tu correo está registrado, recibirás un enlace.';
    } catch (Exception $e) {
        return "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
}
