<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/general/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['correo']) && isset($_POST['contrasenia'])) {
        $correo = $_POST['correo'];
        $contrasenia = $_POST['contrasenia'];

        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $result = mysqli_query($conexion, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $result2 = mysqli_fetch_assoc($result);
            $hash_guardado = $result2['contrasenia'];
            if (password_verify($contrasenia, $hash_guardado)) {
                $_SESSION['id'] = $result2['id_usuario'];
                $_SESSION['tipo_usuario'] = $result2['rol'];
                switch($result2['rol']){
                    case("1"):
                        header('Location: /Zava-php/index.php');
                        exit;
                        break;
                    case("2"):
                        header('Location: /Zava-php/php/comercio/index.php');
                        exit;
                        break;
                    case("3"):
                        
                        exit;
                        break;
                    default:
                        header('Location: /Zava-php/index.php');
                        exit;
                        break;
                }
                
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
        <form action="inicioSesion.php" method="POST" class="form-iniciar-sesion">
            <div class="cont-input">
                <label class="lbl-iniciar-sesion" for="correo">Correo</label>
                <input class="input-iniciar-sesion email" type="text" id="correo" name="correo" required>
            </div>
            <div class="cont-input">
                <label class="lbl-iniciar-sesion" for="contrasenia">Contraseña</label>
                <input class="input-iniciar-sesion contrasenia" type="password" id="contrasenia" name="contrasenia" required>
            </div>
        <?php if ($mensaje): ?>
            <p style="color:red;"><?= $mensaje ?></p>
        <?php endif; ?>
            <a href="/Zava-php/php/general/diferenciacionRegistro.php">¿No tenes cuenta?</a>
            <button type="submit" class="btn-iniciar-sesion">Iniciar sesión</button>
        </form>
    </div>
</main>
        