<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/conexion.php';
$mensaje = '';

// Validar que el usuario venga desde diferenciacionRegistro.php con un rol
if (!isset($_POST['rol']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si accede directamente sin POST, redirigir a diferenciación
    header('Location: diferenciacionRegistro.php');
    exit;
}

// Obtener el rol desde POST (viene del formulario de diferenciación)
$rol_seleccionado = isset($_POST['rol']) ? (int)$_POST['rol'] : 1;

// Incluir la función para enviar correos de verificación
require_once $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/public/mails/enviarVerificacion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    if (
        !empty($_POST['nombre']) &&
        !empty($_POST['apellido']) &&
        !empty($_POST['nombreDeUsuario']) &&
        !empty($_POST['correo']) &&
        !empty($_POST['contrasenia']) &&
        !empty($_POST['confirmarContrasenia']) &&
        isset($_POST['rol'])
    ) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
        $nombreDeUsuario = mysqli_real_escape_string($conexion, $_POST['nombreDeUsuario']);
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $contrasenia = $_POST['contrasenia'];
        $confirmarContrasenia = $_POST['confirmarContrasenia'];
        $rol = (int) $_POST['rol'];

        if ($contrasenia !== $confirmarContrasenia) {
            $mensaje = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        } else {
            $contrasenia_hashed = password_hash($contrasenia, PASSWORD_DEFAULT);
            $token_verificacion = bin2hex(random_bytes(50));
            $token_expiracion = date('Y-m-d H:i:s', strtotime('+1 day'));

            $consulta_correo = "SELECT correo FROM Usuarios WHERE correo='$correo'";
            $resultado_correo = mysqli_query($conexion, $consulta_correo);

            $consulta_nick = "SELECT nickname FROM Usuarios WHERE nickname='$nombreDeUsuario'";
            $resultado_nick = mysqli_query($conexion, $consulta_nick);

            if (mysqli_num_rows($resultado_correo) > 0) {
                $mensaje = "El correo ya está registrado. <a href='diferenciacionRegistro.php'>Vuelve</a> y usa otro.";
            } elseif (mysqli_num_rows($resultado_nick) > 0) {
                $mensaje = "El nombre de usuario ya está registrado. Por favor, elige otro.";
            } else {
                // Prepara la consulta SQL para insertar el nuevo usuario con el token de verificación.
                $sql = "INSERT INTO Usuarios (nombre, apellido, nickname, correo, telefono, contrasenia, id_rol, token_verificacion, token_expiracion) VALUES ('$nombre', '$apellido', '$nombreDeUsuario', '$correo', NULL, '$contrasenia_hashed', '$rol', '$token_verificacion', '$token_expiracion')";

                // Ejecuta la consulta y, si tiene éxito, procede a enviar el correo.
                $resultado_insert = mysqli_query($conexion, $sql);

                if ($resultado_insert) {
                    $envio_correo = enviarCorreoVerificacion($correo, $nombre, $token_verificacion);

                    if ($envio_correo === true) {
                        // Redirigir a pantalla de mensaje de verificación
                        echo '<form id="postRedirect" action="/Zava/public/public/pantallaCarga.php" method="POST">
                                <input type="hidden" name="mensaje" value="¡Registro exitoso! Revisa tu correo y verifica tu cuenta">
                                <input type="hidden" name="destino" value="/Zava/login">
                              </form>
                              <script>document.getElementById("postRedirect").submit();</script>';
                        exit;
                    } else {
                        // Si el envío falla, se muestra el error devuelto por la función.
                        $mensaje = $envio_correo;
                    }
                } else {
                    // Si la consulta SQL para insertar al usuario falla, se muestra un error de la base de datos.
                    $mensaje = "Error al registrar usuario: " . mysqli_error($conexion);
                }
            }
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <title>Zava</title>
    <link rel="stylesheet" href="/Zava/css/public/general.css">
    <link rel="stylesheet" href="/Zava/css/public/accesoUsuario.css">

</head>

<body>
    <main class="contenedor-principal">
        <section class="contenedor-general registrarse">
            <div class="imagen-titulo">
                <img src="/Zava/css/recursos/logos/Principal 2.0.png">
            </div>
            <form class="contenedor-form" action="/Zava/registrarse" method="POST">
                <input type="hidden" name="rol" value="<?php echo htmlspecialchars($rol_seleccionado); ?>">
                <h6 class="media-negrita color-primario">Registrarse</h6>
                <div class="contenedor-inputs">

                    <div class="contenedor-input-doble">
                        <div class="contenedor-input">
                            <p class="pequenio medium color-primario">Nombre</p>
                            <input type="text" class="input input-nombre" name="nombre" maxlength="50" required>
                        </div>
                        <div class="contenedor-input">
                            <p class="pequenio medium color-primario">Apellido</p>
                            <input type="text" class="input input-apellido" name="apellido" maxlength="50" required>
                        </div>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Nombre usuario</p>
                        <input type="text" class="input input-nombreDeUsuario" name="nombreDeUsuario" maxlength="50"
                            required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Email</p>
                        <input type="email" class="input input-email" name="correo" maxlength="100" required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Contraseña</p>
                        <input type="password" class="input input-contrasenia" name="contrasenia" maxlength="255"
                            required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Confirmar contraseña</p>
                        <input type="password" class="input input-contrasenia" name="confirmarContrasenia"
                            maxlength="255" required>
                    </div>
                </div>
                <a href="iniciarSesion.php" class="pequenio light color-primario vinculo-registrarse">¿Ya tienes cuenta?
                    <b class="color-primario">Iniciar sesión</b></a>
                <button type="submit" class="btn btn-enviar-formulario pequenio color-secundario">Registrarse</button>
            </form>
        </section>
    </main>
</body>

</html>