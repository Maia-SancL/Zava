<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/general/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['correo']) && isset($_POST['contrasenia'])) {
        $correo = $_POST['correo'];
        $pass = $_POST['contrasenia'];
        $pass_MD5 = md5($pass);

        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $result = mysqli_query($conexion, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $result2 = mysqli_fetch_assoc($result);
            if ($pass_MD5 == $result2['contrasenia']) {
                $_SESSION['id'] = $result2['id_usuario'];
                $_SESSION['correo'] = $result2['correo'];
                $_SESSION['tipo_usuario'] = $result2['rol'] === '1' ? 'administrador' : ($result2['rol'] === '2' ? 'cliente' : 'vendedor');
                header("Location: index.php");
                exit;
            } else {
                $mensaje = 'Contraseña incorrecta';
            }
        } else {
            $mensaje = 'No existe la cuenta';
        }
    }
}
?>
<header>
    <link rel="stylesheet" href="/Zava-php/css/inicioSesion.css">
</header>
<main>
    <div class="imagen-principal">
         <img class="img-iniciar-sesion" src="/Zava-php/css/recursos/Principal.png"> 
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
            <a href="/Zava-php/php/general/register.php">¿No tenes cuenta?</a>
            <button type="submit" class="btn-iniciar-sesion">Iniciar sesión</button>
        </form>
    </div>
</main>
        