<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/general/conexion.php';

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
                    
                    // Definir tipo de usuario basado en el rol
                    switch($usuario['id_rol']) {
                        case 1: $_SESSION['tipo_usuario'] = 'Usuario'; break;
                        case 2: $_SESSION['tipo_usuario'] = 'Comercio'; break;
                        case 3: $_SESSION['tipo_usuario'] = 'Admin'; break;
                        default: $_SESSION['tipo_usuario'] = 'Usuario';
                    }
                    
                    echo '
                    <form id="postRedirect" action="/Zava/php/componentes/pantallaCarga.php" method="POST">
                        <input type="hidden" name="mensaje" value="Iniciando sesión...">
                        <input type="hidden" name="destino" value="/Zava/index.php">
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
<header>
    <link rel="stylesheet" href="/Zava/css/general/inicioSesion.css">
</header>
<main>
    <div class="imagen-principal">
         <img class="img-iniciar-sesion" src="/Zava/css/recursos/Principal.png"> 
    </div>
    <div class="cont-formulario-iniciar-sesion">
        <h4 class="titulo-iniciar-sesion">Iniciar sesión</h4>
        <?php if ($mensaje): ?>
            <p style="color:red;"><?= $mensaje ?></p>
        <?php endif; ?>
        <form action="inicioSesion.php" method="POST" class="form-iniciar-sesion">
            <div class="cont-input">
                <label class="lbl-iniciar-sesion" for="correo">Correo</label>
                <input class="input-iniciar-sesion email" type="text" id="correo" name="correo" required>
            </div>
            <div class="cont-input">
                <label class="lbl-iniciar-sesion" for="contrasenia">Contraseña</label>
                <input class="input-iniciar-sesion contrasenia" type="password" id="contrasenia" name="contrasenia" required>
            </div>
            <a href="/Zava/php/general/diferenciacionRegistro.php">¿No tenes cuenta?</a>
            <a href="/Zava/php/general/solicitarRestauracion.php">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btn-iniciar-sesion">Iniciar sesión</button>
        </form>
    </div>
</main>
        