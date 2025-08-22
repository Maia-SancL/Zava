<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';

$mensaje = '';
$token_valido = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validar el token
    $token_escaped = mysqli_real_escape_string($conexion, $token);
    $sql = "SELECT id_usuario, token_restauracion_expiracion FROM Usuarios WHERE token_restauracion = '{$token_escaped}'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        if (strtotime($usuario['token_restauracion_expiracion']) > time()) {
            $token_valido = true;
        } else {
            $mensaje = 'El enlace de restauración ha expirado. Por favor, solicita uno nuevo.';
        }
    } else {
        $mensaje = 'El enlace de restauración no es válido.';
    }
} else {
    $mensaje = 'No se proporcionó un token de restauración.';
}

// Lógica para actualizar la contraseña
if ($token_valido && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['contrasenia']) && !empty($_POST['confirmarContrasenia'])) {
        if ($_POST['contrasenia'] === $_POST['confirmarContrasenia']) {
            $nueva_contrasenia = password_hash($_POST['contrasenia'], PASSWORD_DEFAULT);
            $token_post = $_POST['token'];

            // Actualizar contraseña y anular token. ADVERTENCIA: Se omite el escapado de $nueva_contrasenia porque password_hash es seguro.
            $token_post_escaped = mysqli_real_escape_string($conexion, $token_post);
            $sql_update = "UPDATE Usuarios SET contrasenia = '{$nueva_contrasenia}', token_restauracion = NULL, token_restauracion_expiracion = NULL WHERE token_restauracion = '{$token_post_escaped}'";
            
            if (mysqli_query($conexion, $sql_update)) {
                $mensaje = 'Tu contraseña ha sido actualizada con éxito. Ya puedes <a href="inicioSesion.php">iniciar sesión</a>.';
                $token_valido = false; // Ocultar el formulario después del éxito
            } else {
                $mensaje = 'Hubo un error al actualizar tu contraseña. Inténtalo de nuevo.';
            }
        } else {
            $mensaje = 'Las contraseñas no coinciden.';
        }
    } else {
        $mensaje = 'Por favor, completa todos los campos.';
    }
}

?>

<link rel="stylesheet" href="/Zava/css/cliente/verificar.css">
<main>
    <div class="verificacion-container">
        <h2>Establecer Nueva Contraseña</h2>

        <?php if ($mensaje): ?>
            <p style="color:red;"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <?php if ($token_valido): ?>
            <form action="restaurarContrasenia.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="cont-input">
                    <label for="contrasenia">Nueva Contraseña</label>
                    <input type="password" id="contrasenia" name="contrasenia" required>
                </div>
                
                <div class="cont-input">
                    <label for="confirmarContrasenia">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirmarContrasenia" name="confirmarContrasenia" required>
                </div>

                <button type="submit" class="btn-registrarse">Actualizar Contraseña</button>
            </form>
        <?php endif; ?>
    </div>
</main>
