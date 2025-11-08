<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/conexion.php';
$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['correo']) && isset($_POST['contrasenia'])) {
        $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
        $pass = $_POST['contrasenia'];

        $sql = "SELECT * FROM Usuarios WHERE correo = '$correo' AND activo = 1";
        $result = mysqli_query($conexion, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $usuario = mysqli_fetch_assoc($result);

            // Verificar la contraseña usando password_verify
            if (password_verify($pass, $usuario['contrasenia'])) {
                
                // Comprobar si la cuenta ha sido verificada
                if ($usuario['verificado']) {
                    $_SESSION['id'] = $usuario['id_usuario'];
                    $_SESSION['correo'] = $usuario['correo'];
                    $_SESSION['nickname'] = $usuario['nickname'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['apellido'] = $usuario['apellido'];
                    $_SESSION['id_rol'] = $usuario['id_rol'];
                    $_SESSION['foto'] = $usuario['foto'];
                    $_SESSION['usuario'] = $usuario;
                    
                    // Definir tipo de usuario basado en el rol y redirigir
                    switch($usuario['id_rol']) {
                        case 1: 
                            $_SESSION['tipo_usuario'] = 'Usuario';
                            break;
                        case 2: 
                            $_SESSION['tipo_usuario'] = 'Comercio';
                            break;
                        case 3: 
                            $_SESSION['tipo_usuario'] = 'Admin';
                            break;
                        default: 
                            $_SESSION['tipo_usuario'] = 'Usuario';
                            break;
                    }
                    
                    // Todos redirigen a /Zava/inicio
                    echo '
                    <form id="postRedirect" action="/Zava/public/public/pantallaCarga.php" method="POST">
                        <input type="hidden" name="mensaje" value="Iniciando sesión...">
                        <input type="hidden" name="destino" value="/Zava/inicio">
                    </form>
                    <script>document.getElementById("postRedirect").submit();</script>';
                    exit;
                } else {
                    $mensaje = 'Tu cuenta aún no ha sido verificada. Por favor, revisa tu correo electrónico.';
                }
            } else {
                $mensaje = 'Correo o contraseña incorrectos.';
            }
        } else {
            $mensaje = 'Correo o contraseña incorrectos.';
        }
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
        <section class="contenedor-general iniciar-sesion">
            <div class="imagen-titulo">
                <img src="/Zava/css/recursos/logos/Principal 2.0.png">
            </div>
            <form class="contenedor-form" action="/Zava/login" method="POST">
                <h6 class="media-negrita color-primario">Iniciar sesión</h6>
                <div class="contenedor-inputs">
                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Email</p>
                        <input type="email" class="input input-email" name="correo" required>
                    </div>

                    <div class="contenedor-input">
                        <p class="pequenio medium color-primario">Contraseña</p>
                        <input type="password" class="input input-contrasenia" name="contrasenia" required>
                    </div>
                </div>
                <p class="pequenio media-negrita color-error"><?= $mensaje; ?></p>
                <a href="/Zava/registro" class="pequenio light color-primario vinculo-registrarse">¿No tienes cuenta? <b class="color-primario">Registrarme</b></a>
                <button type="submit" class="btn btn-enviar-formulario pequenio color-secundario">Iniciar
                    sesión</button>
            </form>
        </section>
    </main>
</body>

</html>