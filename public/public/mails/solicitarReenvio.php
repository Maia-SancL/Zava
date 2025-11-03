<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';

$mensaje = '';
$token = isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '';

if (empty($token)) {
    $mensaje = 'No se proporcionó un token válido.';
} else {
    $mensaje = '¿Estás seguro de que quieres recibir un nuevo correo de verificación?';
}
?>

<link rel="stylesheet" href="/Zava/css/verificar.css">
<main>
    <div class="verificacion-container">
        <h2>Reenviar Correo de Verificación</h2>
        <p><?php echo $mensaje; ?></p>
        
        <?php if (!empty($token)): ?>
            <form action="reenviarVerificacion.php" method="POST" style="margin-top: 1rem;">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <button type="submit" class="btn-registrarse">Sí, enviar nuevo correo</button>
            </form>
        <?php endif; ?>
    </div>
</main>
