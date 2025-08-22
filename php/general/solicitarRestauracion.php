<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/mails/gestionRestauracion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['correo'])) {
    $correo = $_POST['correo'];
    $mensaje = solicitarRestauracionContrasenia($conexion, $correo);
}
?>

<link rel="stylesheet" href="/Zava/css/cliente/verificar.css">
<main>
    <div class="verificacion-container">
        <h2>Restaurar Contraseña</h2>
        <p>Ingresa tu correo electrónico para recibir un enlace de restauración.</p>
        
        <?php if ($mensaje): ?>
            <p style="color:green;"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form action="solicitarRestauracion.php" method="POST" style="margin-top: 1rem;">
            <div class="cont-input">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <button type="submit" class="btn-registrarse">Enviar Enlace</button>
        </form>
    </div>
</main>
