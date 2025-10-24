<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';

session_start();
$mensaje = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 1. Buscar el usuario con el token.
    $stmt_user = $conexion->prepare("SELECT id_usuario, token_eliminacion_expiracion FROM Usuarios WHERE token_eliminacion = ?");
    $stmt_user->bind_param("s", $token);
    $stmt_user->execute();
    $resultado = $stmt_user->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt_user->close();

    if ($usuario) {
        $ahora = new DateTime();
        $expiracion = new DateTime($usuario['token_eliminacion_expiracion']);

        // 2. Verificar si el token ha expirado.
        if ($ahora > $expiracion) {
            $mensaje = 'El enlace de eliminación ha expirado. Por favor, solicita la eliminación de nuevo desde tu perfil.';
        } else {
            $id_usuario = $usuario['id_usuario'];

            // 3. Comprobar si el usuario tiene pedidos.
            $stmt_pedidos = $conexion->prepare("SELECT id_pedido FROM Pedidos WHERE id_usuario = ?");
            $stmt_pedidos->bind_param("i", $id_usuario);
            $stmt_pedidos->execute();
            $resultado_pedidos = $stmt_pedidos->get_result();
            $stmt_pedidos->close();

            if ($resultado_pedidos->num_rows > 0) {
                $mensaje = 'No se puede eliminar la cuenta porque tienes pedidos asociados. Contacta con soporte para más información.';
            } else {
                // 4. Iniciar transacción.
                $conexion->begin_transaction();
                try {
                    // Eliminar datos dependientes (los que no tienen ON DELETE CASCADE)
                    $conexion->query("DELETE FROM Productos WHERE id_usuario = $id_usuario");
                    $conexion->query("DELETE FROM Recetas WHERE id_usuario = $id_usuario");
                    
                    // Finalmente, eliminar el usuario. El resto de datos se borra por ON DELETE CASCADE.
                    $stmt_delete = $conexion->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
                    $stmt_delete->bind_param("i", $id_usuario);
                    $stmt_delete->execute();
                    $stmt_delete->close();

                    // 5. Confirmar transacción.
                    $conexion->commit();
                    $mensaje = 'Tu cuenta ha sido eliminada permanentemente. Lamentamos verte partir.';
                    
                    // Destruir la sesión.
                    session_unset();
                    session_destroy();

                } catch (mysqli_sql_exception $exception) {
                    $conexion->rollback();
                    $mensaje = 'Hubo un error al intentar eliminar tu cuenta. Por favor, contacta con soporte.';
                }
            }
        }
    } else {
        $mensaje = 'El enlace de eliminación no es válido o ya ha sido utilizado.';
    }
} else {
    $mensaje = 'No se proporcionó un token de eliminación.';
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminación de Cuenta</title>
    <link rel="stylesheet" href="/Zava/css/general/confirmacion.css">
</head>
<body>
    <div class="container">
        <h1>Eliminación de Cuenta</h1>
        <p><?php echo $mensaje; ?></p>
        <a href="/Zava/index.php" class="btn-login">Volver al Inicio</a>
    </div>
</body>
</html>
