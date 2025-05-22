<?php
session_start();
include('conexion.php');

$mensaje = '';
$rol = $_POST['rol'];

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
    <h2>Registro de usuario</h2>
    <?php if ($mensaje): ?>
        <p style="color:red;"><?= $mensaje ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required><br>

        <label for="nombre">Nombre de usuario:</label>
        <input type="text" name="nombreDeUsuario" id="nombreDeUsuario" required><br>

        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" id="correo" required><br>

        <label for="contrasenia">Contraseña:</label>
        <input type="password" name="contrasenia" id="contrasenia" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required><br>

        <input type="hidden" name="rol" value="<?php echo $_POST['rol']; ?>">

        <button type="submit">Registrarse</button>
    </form>