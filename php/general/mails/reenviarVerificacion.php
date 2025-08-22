<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/mails/reenvioTokens.php';

$mensaje = '';

// Comprobamos si la solicitud es de tipo POST y si se ha proporcionado un token.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token_antiguo = $_POST['token'];
    // Llamamos a la función para reenviar el correo.
    $mensaje = reenviarCorreoDeVerificacion($conexion, $token_antiguo);
} else {
    // Si se accede a esta página directamente sin confirmar, se redirige o muestra un error.
    $mensaje = 'Solicitud inválida. Por favor, inicia el proceso de reenvío desde el enlace original.';
}
?>

<link rel="stylesheet" href="/Zava/css/verificar.css">
<main>
    <div class="verificacion-container">
        <h2>Reenviar Verificación</h2>
        <p><?php echo $mensaje; ?></p>
    </div>
</main>
