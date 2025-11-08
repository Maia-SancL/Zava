<?php
// Incluir los archivos de PHPMailer manualmente
require $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/phpMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/phpMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/phpMailer/Exception.php';

// Importar las clases de PHPMailer al espacio de nombres global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envia un correo de verificacion al usuario.
 *
 * @param string $correo El correo del destinatario.
 * @param string $nombre El nombre del destinatario.
 * @param string $token El token de verificacion.
 * @return bool|string Devuelve true si el correo se envio, o un string con el mensaje de error si fallo.
 */
function enviarCorreoVerificacion($correo, $nombre, $token) {
    $mail = new PHPMailer(true);

    try {
        // ----- CONFIGURACION DEL SERVIDOR SMTP -----
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP de Gmail.
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Zava.NoReply@gmail.com'; // IMPORTANTE: Reemplazar con correo.
        $mail->Password   = 'gexfupvuftkmpdre'; // IMPORTANTE: Reemplazar con tu contraseña de aplicacion.
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // ----- REMITENTE Y DESTINATARIO -----
        $mail->setFrom('Zava.NoReply@gmail.com', 'Equipo de Zava');
        $mail->addAddress($correo, $nombre);

        // ----- CONTENIDO DEL CORREO -----
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta en Zava';
        $verification_link = "http://" . $_SERVER['HTTP_HOST'] . "/Zava/verificar?token={$token}";
        $mail->Body    = "¡Gracias por registrarte! Por favor, haz clic en el siguiente enlace para verificar tu cuenta: <a href='{$verification_link}'>Verificar cuenta</a>";
        $mail->AltBody = "Gracias por registrarte! Por favor, usa el siguiente enlace para verificar tu cuenta: {$verification_link}";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
}
