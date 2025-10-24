<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/phpMailer/Exception.php';

/**
 * Inicia el proceso de cambio de correo electronico.
 *
 * @param mysqli $conexion La conexion a la base de datos.
 * @param int $id_usuario El ID del usuario.
 * @param string $nuevo_correo La nueva direccion de correo.
 * @return string Mensaje de estado.
 */
function iniciarCambioCorreo($conexion, $id_usuario, $nuevo_correo) {
    // 1. Obtener el correo actual y el nombre del usuario.
    $stmt_user = $conexion->prepare("SELECT correo, nombre FROM Usuarios WHERE id_usuario = ?");
    if (!$stmt_user) return 'Error al preparar la consulta de usuario.';
    $stmt_user->bind_param("i", $id_usuario);
    $stmt_user->execute();
    $resultado = $stmt_user->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt_user->close();

    if (!$usuario) return 'No se encontró el usuario.';

    $correo_actual = $usuario['correo'];
    $nombre_usuario = $usuario['nombre'];

    // 2. Generar token y fecha de expiracion.
    $token = bin2hex(random_bytes(16));
    $expiracion = (new DateTime())->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');

    // 3. Guardar el nuevo correo y el token en la base de datos.
    $stmt_update = $conexion->prepare("UPDATE Usuarios SET nuevo_correo = ?, token_cambio_correo = ?, token_cambio_correo_expiracion = ? WHERE id_usuario = ?");
    if (!$stmt_update) return 'Error al preparar la actualización.';
    $stmt_update->bind_param("sssi", $nuevo_correo, $token, $expiracion, $id_usuario);
    
    if (!$stmt_update->execute()) {
        $stmt_update->close();
        return 'Hubo un problema al iniciar el cambio de correo. Inténtalo de nuevo.';
    }
    $stmt_update->close();

    // 4. Enviar el correo de confirmacion al correo actual.
    return enviarCorreoConfirmacionCambio($correo_actual, $nombre_usuario, $token);
}

/**
 * Envía el correo con el enlace de confirmación para el cambio de email.
 *
 * @param string $correo_actual
 * @param string $nombre
 * @param string $token
 * @return string Mensaje de estado.
 */
function enviarCorreoConfirmacionCambio($correo_actual, $nombre, $token) {
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
        $mail->addAddress($correo_actual, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu cambio de correo electronico - Zava';
        $link_confirmacion = "http://" . $_SERVER['HTTP_HOST'] . "/Zava/php/general/confirmarCambioCorreo.php?token={$token}";
        $mail->Body    = "Has solicitado cambiar tu direccion de correo electronico. Para confirmar, haz clic en el siguiente enlace: <a href='{$link_confirmacion}'>Confirmar cambio</a>. Si no solicitaste esto, ignora este mensaje.";
        $mail->AltBody = "Usa el siguiente enlace para confirmar tu cambio de correo: {$link_confirmacion}";

        $mail->send();
        return 'Se ha enviado un enlace de confirmación a tu correo actual.';
    } catch (Exception $e) {
        return "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
}
