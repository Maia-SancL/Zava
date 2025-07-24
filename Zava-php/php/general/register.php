<link rel="stylesheet" href="/Zava-php/css/registrarse.css">
<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/general/conexion.php';

$mensaje = '';

if (isset($_GET['rol']) && in_array($_GET['rol'], ['1','2'])) {
    $rol = $_GET['rol'];
}else{
    $rol='1';
}
//Verificamos si se recibio el rol y si esta dentro de los permitidos 

if (isset($_POST['registrarse'])) {
    //Validar campos requeridos


    $campos_requeridos = ['nombre', 'apellido', 'nombreDeUsuario', 'correo', 'contrasenia', 'validar-contrasenia', 'prefijo','telefono'];
    foreach ($campos_requeridos as $campo) {
        if (empty(trim($_POST[$campo] ?? ''))) {
            $mensaje = "Completa todos los campos.";
            break;
        }
    }

    if (!$mensaje) {
        //Extraer y limpiar datos
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $nombreDeUsuario = trim($_POST['nombreDeUsuario']);
        $correo = trim($_POST['correo']);
        $contrasenia = $_POST['contrasenia'];
        $confirmarContrasenia = $_POST['validar-contrasenia'];
        $prefijo = $_POST['prefijo'];
        $telefono = trim($_POST['telefono']);
        $rol = isset($_POST['rol']) && in_array($_POST['rol'], ['1', '2']) ? $_POST['rol'] : '1';


        //Verificar si el nombre de user ya esta registrado
        $verificar_nombreUser_query = "SELECT nickname FROM usuarios WHERE nickname = '$nombreDeUsuario'";
        $verificar_nombreUser_result = mysqli_query($conexion, $verificar_nombreUser_query);

        if ($verificar_nombreUser_result && mysqli_num_rows( $verificar_nombreUser_result) > 0) {
                $mensaje = "El usuario ya está registrado. Usa otro.";
        }else{
            //Validar que las contraseñas si coincidan
            if ($contrasenia !== $confirmarContrasenia) {
                $mensaje = "Las contraseñas no coinciden.";
            } else {
                //Verificar si el correo ya esta registrado
                $verificar_correo_query = "SELECT correo FROM usuarios WHERE correo = '$correo'";
                $verificar_correo_result = mysqli_query($conexion, $verificar_correo_query);

                if ($verificar_correo_result && mysqli_num_rows($verificar_correo_result) > 0) {
                    $mensaje = "El correo ya está registrado. Usa otro.";
                } else {

                    // Encriptar la contraseña
                    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

                    //Preparar numero de teléfono con prefijo
                    $numero_telefono = $prefijo . $telefono;
                    //Insertar nuevo usuario
                    $insert_query = "INSERT INTO usuarios (nombre, apellido, nickname, correo, contrasenia, telefono, rol) VALUES ('$nombre', '$apellido', '$nombreDeUsuario', '$correo', '$contrasenia_hash', '$numero_telefono', '$rol')";
                    $insert_result = mysqli_query($conexion, $insert_query);

                    if ($insert_result) {
                        echo '
                        <form id="postRedirect" action="/Zava-php/php/componentes/pantallaCarga.php" method="POST">
                            <input type="hidden" name="mensaje" value="Registro exitoso. Redirigiendo a inicio de sesión...">
                            <input type="hidden" name="destino" value="/Zava-php/php/general/inicioSesion.php">
                        </form>
                        <script>document.getElementById("postRedirect").submit();</script>';
                        exit;
                    } else {
                        $mensaje = "Error al registrar usuario. Inténtalo de nuevo más tarde.";
                    }
                }
            }
        }
    }
}
?>
<main>
    <div class="imagen-principal">
        <img class="img-registro" src="/Zava-php/css/recursos/Principal.png" alt="Imagen principal">
    </div>
    <div class="cont-registro">
        <h4 class="titulo-registro">Registrarse</h4>

        <form action="register.php" method="POST">

            <div class="cont-dividido">
                <div class="superponer">
                    <label class="lbl-registro" for="nombre">Nombre</label>
                    <input class="input-registro" type="text" name="nombre" required maxlength="50" id="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="superponer">
                    <label class="lbl-registro" for="apellido">Apellido</label>
                    <input class="input-registro-apellido" type="text" name="apellido" required maxlength="50" id="apellido" value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
                </div>
            </div>

            <div class="cont-input">
                <label class="lbl-registro" for="nombreDeUsuario">Nombre de Usuario</label>
                <input class="input-registro" id="nombreDeUsuario" type="text" name="nombreDeUsuario" required maxlength="50" value="<?= htmlspecialchars($_POST['nombreDeUsuario'] ?? '') ?>">
            </div>

            <div class="cont-input">
                <label class="lbl-registro" for="correo">Email</label>
                <input class="input-registro" type="email" name="correo" id="correo" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
            </div>

            <div class="cont-dividido">
                <div class="superponer">
                    <label class="lbl-registro" for="prefijo">Prefijo</label>
                    <select class="input-registro" id="prefijo" name="prefijo">
                        <option value="+54" <?= (($_POST['prefijo'] ?? '') == '+54') ? 'selected' : '' ?>>+54 🇦🇷</option>
                        <option value="+49" <?= (($_POST['prefijo'] ?? '') == '+49') ? 'selected' : '' ?>>+49 🇩🇪</option>
                        <option value="+61" <?= (($_POST['prefijo'] ?? '') == '+61') ? 'selected' : '' ?>>+61 🇭🇲</option>
                        <option value="+43" <?= (($_POST['prefijo'] ?? '') == '+43') ? 'selected' : '' ?>>+43 🇦🇹</option>
                        <option value="+32" <?= (($_POST['prefijo'] ?? '') == '+32') ? 'selected' : '' ?>>+32 🇧🇪</option>
                        <option value="+55" <?= (($_POST['prefijo'] ?? '') == '+55') ? 'selected' : '' ?>>+55 🇧🇷</option>
                        <option value="+1" <?= (($_POST['prefijo'] ?? '') == '+1') ? 'selected' : '' ?>>+1 🇨🇦</option>
                        <option value="+56" <?= (($_POST['prefijo'] ?? '') == '+56') ? 'selected' : '' ?>>+56 🇨🇱</option>
                        <option value="+57" <?= (($_POST['prefijo'] ?? '') == '+57') ? 'selected' : '' ?>>+57 🇨🇴</option>
                        <option value="+506" <?= (($_POST['prefijo'] ?? '') == '+506') ? 'selected' : '' ?>>+506 🇨🇷</option>
                        <option value="+385" <?= (($_POST['prefijo'] ?? '') == '+385') ? 'selected' : '' ?>>+385 🇭🇷</option>
                        <option value="+53" <?= (($_POST['prefijo'] ?? '') == '+53') ? 'selected' : '' ?>>+53 🇨🇺</option>
                        <option value="+45" <?= (($_POST['prefijo'] ?? '') == '+45') ? 'selected' : '' ?>>+45 🇩🇰</option>
                        <option value="+1-809" <?= (($_POST['prefijo'] ?? '') == '+1-809') ? 'selected' : '' ?>>+1-809 🇩🇴</option>
                        <option value="+1" <?= (($_POST['prefijo'] ?? '') == '+1') ? 'selected' : '' ?>>+1 🇺🇲</option>
                        <option value="+593" <?= (($_POST['prefijo'] ?? '') == '+593') ? 'selected' : '' ?>>+593 🇪🇨</option>
                        <option value="+503" <?= (($_POST['prefijo'] ?? '') == '+503') ? 'selected' : '' ?>>+503 🇸🇻</option>
                        <option value="+358" <?= (($_POST['prefijo'] ?? '') == '+358') ? 'selected' : '' ?>>+358 🇫🇮</option>
                        <option value="+33" <?= (($_POST['prefijo'] ?? '') == '+33') ? 'selected' : '' ?>>+33 🇲🇫</option>
                        <option value="+30" <?= (($_POST['prefijo'] ?? '') == '+30') ? 'selected' : '' ?>>+30 🇬🇷</option>
                        <option value="+502" <?= (($_POST['prefijo'] ?? '') == '+502') ? 'selected' : '' ?>>+502 🇬🇹</option>
                        <option value="+504" <?= (($_POST['prefijo'] ?? '') == '+504') ? 'selected' : '' ?>>+504 🇭🇳</option>
                        <option value="+354" <?= (($_POST['prefijo'] ?? '') == '+354') ? 'selected' : '' ?>>+354 🇮🇸</option>
                        <option value="+353" <?= (($_POST['prefijo'] ?? '') == '+353') ? 'selected' : '' ?>>+353 🇮🇪</option>
                        <option value="+39" <?= (($_POST['prefijo'] ?? '') == '+39') ? 'selected' : '' ?>>+39 🇮🇹</option>
                        <option value="+1-876" <?= (($_POST['prefijo'] ?? '') == '+1-876') ? 'selected' : '' ?>>+1-876 🇯🇲</option>
                        <option value="+81" <?= (($_POST['prefijo'] ?? '') == '+81') ? 'selected' : '' ?>>+81 🇯🇵</option>
                        <option value="+52" <?= (($_POST['prefijo'] ?? '') == '+52') ? 'selected' : '' ?>>+52 🇲🇽</option>
                        <option value="+377" <?= (($_POST['prefijo'] ?? '') == '+377') ? 'selected' : '' ?>>+377 🇲🇨</option>
                        <option value="+212" <?= (($_POST['prefijo'] ?? '') == '+212') ? 'selected' : '' ?>>+212 🇲🇦</option>
                        <option value="+31" <?= (($_POST['prefijo'] ?? '') == '+31') ? 'selected' : '' ?>>+31 🇳🇱</option>
                        <option value="+64" <?= (($_POST['prefijo'] ?? '') == '+64') ? 'selected' : '' ?>>+64 🇳🇿</option>
                        <option value="+505" <?= (($_POST['prefijo'] ?? '') == '+505') ? 'selected' : '' ?>>+505 🇳🇮</option>
                        <option value="+47" <?= (($_POST['prefijo'] ?? '') == '+47') ? 'selected' : '' ?>>+47 🇸🇯</option>
                        <option value="+507" <?= (($_POST['prefijo'] ?? '') == '+507') ? 'selected' : '' ?>>+507 🇵🇦</option>
                        <option value="+595" <?= (($_POST['prefijo'] ?? '') == '+595') ? 'selected' : '' ?>>+595 🇵🇾</option>
                        <option value="+48" <?= (($_POST['prefijo'] ?? '') == '+48') ? 'selected' : '' ?>>+48 🇵🇱</option>
                        <option value="+351" <?= (($_POST['prefijo'] ?? '') == '+351') ? 'selected' : '' ?>>+351 🇵🇹</option>
                        <option value="+1-787" <?= (($_POST['prefijo'] ?? '') == '+1-787') ? 'selected' : '' ?>>+1-787 🇵🇷</option>
                        <option value="+7" <?= (($_POST['prefijo'] ?? '') == '+7') ? 'selected' : '' ?>>+7 🇷🇺</option>
                        <option value="+34" <?= (($_POST['prefijo'] ?? '') == '+34') ? 'selected' : '' ?>>+34 🇪🇦</option>
                        <option value="+46" <?= (($_POST['prefijo'] ?? '') == '+46') ? 'selected' : '' ?>>+46 🇸🇪</option>
                        <option value="+41" <?= (($_POST['prefijo'] ?? '') == '+41') ? 'selected' : '' ?>>+41 🇨🇭</option>
                        <option value="+90" <?= (($_POST['prefijo'] ?? '') == '+90') ? 'selected' : '' ?>>+90 🇹🇷</option>
                        <option value="+44" <?= (($_POST['prefijo'] ?? '') == '+44') ? 'selected' : '' ?>>+44 🇬🇧</option>
                        <option value="+82" <?= (($_POST['prefijo'] ?? '') == '+82') ? 'selected' : '' ?>>+82 🇰🇷</option>
                        <option value="+380" <?= (($_POST['prefijo'] ?? '') == '+380') ? 'selected' : '' ?>>+380 🇺🇦</option>
                        <option value="+598" <?= (($_POST['prefijo'] ?? '') == '+598') ? 'selected' : '' ?>>+598 🇺🇾</option>
                        <option value="+58" <?= (($_POST['prefijo'] ?? '') == '+58') ? 'selected' : '' ?>>+58 🇻🇪</option>    
                    </select>
                </div>
                <div class="superponer">
                    <label class="lbl-registro" for="telefono">Número Telefónico</label>
                    <input class="input-registro-telefonico" type="text" name="telefono" id="telefono" required value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                </div>
            </div>

            <div class="cont-input">
                <label class="lbl-registro" for="contrasenia">Contraseña</label>
                <input class="input-registro" id="contrasenia" type="password" name="contrasenia" required maxlength="30">
            </div>

            <div class="cont-input">
                <label class="lbl-registro" for="validar-contrasenia">Confirmar contraseña</label>
                <input class="input-registro" id="input_confirm_contrasenia" type="password" name="validar-contrasenia" required maxlength="30">
            </div>

            <input type="hidden" name="rol" value="<?= htmlspecialchars($rol) ?>">
            <?php if ($mensaje): ?>
                <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <button type="submit" class="btn-registrarse" name="registrarse">Registrarse</button>
        </form>
    </div>
</main>
