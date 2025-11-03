<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/mails/gestionTokens.php';

$resultado_verificacion = '';
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $resultado_verificacion = verificarTokenDeCuenta($conexion, $token);
} else {
    $resultado_verificacion = 'No se proporcionó un token de verificación.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Cuenta</title>
    <link rel="stylesheet" href="/Zava/css/general/confirmacion.css">
</head>
<body>
    <div class="container">
        <?php if ($resultado_verificacion === 'success'): ?>
            <?php
            echo '<form id="postRedirect" action="/Zava/php/componentes/pantallaCarga.php" method="POST">
                    <input type="hidden" name="mensaje" value="¡Cuenta verificada! Redirigiendo al inicio de sesión...">
                    <input type="hidden" name="destino" value="/Zava/php/general/inicioSesion.php">
                  </form>
                  <script>document.getElementById("postRedirect").submit();</script>';
            exit;
            ?>
        <?php else: ?>
            <h1>Error de Verificación</h1>
            <p><?php echo $resultado_verificacion; ?></p>
            <a href="/Zava/php/general/inicioSesion.php" class="btn-login">Volver</a>
        <?php endif; ?>
    </div>
</body>
</html>
