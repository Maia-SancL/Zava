<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/general/conexion.php';
$mensaje = '';

if (
    isset($_POST['nombre']) &&
    isset($_POST['apellido']) &&
    isset($_POST['nombreDeUsuario']) &&
    isset($_POST['correo']) &&
    isset($_POST['contrasenia']) &&
    isset($_POST['telefono'])
) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombreDeUsuario = $_POST['nombreDeUsuario'];
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];
    $telefono = $_POST['telefono'];

    // Encriptar la contraseña
    $contrasenia_MD5 = md5($contrasenia);

    // Verificar si el correo ya está registrado
    $consulta = "SELECT correo FROM usuarios WHERE correo='$correo'";
    $consulta1 = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($consulta1) > 0) {
        $mensaje = "El correo ya está registrado. <a href='register.php'>Vuelve</a> y usa otro.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, apellido, nickname, correo, contrasenia, telefono, rol) VALUES ('$nombre', '$apellido', '$nombreDeUsuario', '$correo', '$contrasenia_MD5', '$telefono', '$rol')";
        $result = mysqli_query($conexion, $sql);
        if ($result) {
            $mensaje = "Registro exitoso. Redirigiendo al login...";
            header("Refresh:2; url=login.php");
            exit;
        } else {
            $mensaje = "Error al registrar usuario.";
        }
        echo $mensaje;
    }
}
?>
<link rel="stylesheet" href="/Zava-php/css/registrarse.css">
<main>
<div class="imagen-principal">
            <img class="img-registro" src="/Zava-php/css/recursos/Principal.png">
        </div>
        <div class="cont-registro">
            <h4 class="titulo-registro">Registrarse</h4>
                <?php if ($mensaje): ?>
                    <p style="color:red;"><?= $mensaje ?></p>
                <?php endif; ?>
                <form action="register.php" method="POST">

                <div class="cont-dividido">
                    <div class="superponer">
                        <label class="lbl-registro" for="nombre">Nombre</label>
                        <input class="input-registro" type="text" name="nombre" required maxlength="50" id="nombre">
                    </div>
                    <div class="superponer">
                        <label class="lbl-registro" for="apellido">Apellido</label>
                        <input class="input-registro-apellido" type="text" name="apellido" required maxlength="50" id="apellido"> 
                    </div>
                </div>

                <div class="cont-input">
                    <label class="lbl-registro" for="usuario">Nombre de Usuario</label>
                    <input class="input-registro" id="input_usuario" type="usuario" name="nombreDeUsuario" id="nombreDeUsuario" require> 
                </div>

                <div class="cont-input">
                    <label class="lbl-registro" for="email">Email</label>
                    <input class="input-registro" type="email"  name="correo" id="correo" required> 
                </div>

                <div class="cont-dividido">
                    <div class="superponer">
                        <label class="lbl-registro" for="prefijo">Prefijo</label>
                            <select class="input-registro" id="prefijo" name="prefijo">
                                <option value="+54">+54 🇦🇷</option>
                                <option value="+49">+49 🇩🇪</option>
                                <option value="+61">+61 🇭🇲</option>
                                <option value="+43">+43 🇦🇹</option>
                                <option value="+32">+32 🇧🇪</option>
                                <option value="+55">+55 🇧🇷</option>
                                <option value="+1">+1 🇨🇦</option>
                                <option value="+56">+56 🇨🇱</option>
                                <option value="+57">+57 🇨🇴</option>
                                <option value="+506">+506 🇨🇷</option>
                                <option value="+385">+385 🇭🇷</option>
                                <option value="+53">+53 🇨🇺</option>
                                <option value="+45">+45 🇩🇰</option>
                                <option value="+1-809">+1-809 🇩🇴</option>
                                <option value="+1">+1 🇺🇲</option>
                                <option value="+593">+593 🇪🇨</option>
                                <option value="+503">+503 🇸🇻</option>
                                <option value="+358">+358 🇫🇮</option>
                                <option value="+33">+33 🇲🇫</option>
                                <option value="+30">+30 🇬🇷</option>
                                <option value="+502">+502 🇬🇹</option>
                                <option value="+504">+504 🇭🇳</option>
                                <option value="+354">+354 🇮🇸</option>
                                <option value="+353">+353 🇮🇪</option>
                                <option value="+39">+39 🇮🇹</option>
                                <option value="+1-876">+1-876 🇯🇲</option>
                                <option value="+81">+81 🇯🇵</option>
                                <option value="+52">+52 🇲🇽</option>
                                <option value="+377">+377 🇲🇨</option>
                                <option value="+212">+212 🇲🇦</option>
                                <option value="+31">+31 🇳🇱</option>
                                <option value="+64">+64 🇳🇿</option>
                                <option value="+505">+505 🇳🇮</option>
                                <option value="+47">+47 🇸🇯</option>
                                <option value="+507">+507 🇵🇦</option>
                                <option value="+595">+595 🇵🇾</option>
                                <option value="+48">+48 🇵🇱</option>
                                <option value="+351">+351 🇵🇹</option>
                                <option value="+1-787">+1-787 🇵🇷</option>
                                <option value="+7">+7 🇷🇺</option>
                                <option value="+34">+34 🇪🇦</option>
                                <option value="+46">+46 🇸🇪</option>
                                <option value="+41">+41 🇨🇭</option>
                                <option value="+90">+90 🇹🇷</option>
                                <option value="+44">+44 🇬🇧</option>
                                <option value="+82">+82 🇰🇷</option>
                                <option value="+380">+380 🇺🇦</option>
                                <option value="+598">+598 🇺🇾</option>
                                <option value="+58">+58 🇻🇪</option>
                            </select>
                    </div>
                    <div class="superponer">
                        <label class="lbl-registro" for="telefono">Numero Telefonico</label>
                        <input class="input-registro-telefonico" value="" type="telefono" name="telefono" id="telefono" required> 
                    </div>
                </div>

                <div class="cont-input">
                    <label class="lbl-registro" for="contrasenia">Contraseña</label>
                    <input class="input-registro" id="contrasenia" value="" type="password" name="contrasenia" required maxlength="30"> 
                </div>

                <div class="cont-input">
                    <label class="lbl-registro" for="confirm-contrasenia">Confirmar contraseña</label>
                    <input class="input-registro" id="input_confirm_contrasenia" value="" type="password" placeholder="" name="validar-contrasenia" required maxlength="30">
                </div> 

                <button type="submit" class="btn-registrarse">Registrarse</button>
            </form>
        </div>
</main>