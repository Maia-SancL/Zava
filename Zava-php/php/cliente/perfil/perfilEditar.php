<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/navegador.php';
include_once('conexion.php');


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];


$q = "SELECT nombre, apellido, nickname, correo, telefono, foto FROM usuarios WHERE id_usuario = $id_usuario";
$res = mysqli_query($conexion, $q);
$usuario = mysqli_fetch_assoc($res);


$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $nickname = mysqli_real_escape_string($conexion, $_POST['nickname']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);


    $foto = $usuario['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = 'perfil_' . $id_usuario . '_' . time() . '.' . $ext;
        $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/img/perfiles/' . $nombre_archivo;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $foto = 'img/perfiles/' . $nombre_archivo;
        }
    }

    $update = "UPDATE usuarios SET 
        nombre = '$nombre',
        apellido = '$apellido',
        nickname = '$nickname',
        correo = '$correo',
        telefono = '$telefono',
        foto = '$foto'
        WHERE id_usuario = $id_usuario";
    if (mysqli_query($conexion, $update)) {
        $mensaje = "Perfil actualizado correctamente.";
        // Actualizar datos para mostrar los nuevos
        $usuario = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'nickname' => $nickname,
            'correo' => $correo,
            'telefono' => $telefono,
            'foto' => $foto
        ];
    } else {
        $mensaje = "Error al actualizar el perfil.";
    }
}
?>
<link rel="stylesheet" href="/Zava-php/css/perfil-editar.css">
<main>
    <div class="contenedor-editar-perfil">
        <h2>Editar Perfil</h2>
        <?php if ($mensaje): ?>
            <div class="mensaje-editar"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="form-editar-perfil">
            <div class="foto-perfil-editar">
                <img src="/Zava-php/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil" style="width:100px;height:100px;object-fit:cover;border-radius:50%;">
                <input type="file" name="foto" accept="image/*">
            </div>
            <div class="campos-editar">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                <label>Apellido</label>
                <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
                <label>Usuario</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($usuario['nickname']) ?>" required>
                <label>Correo</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
            </div>
            <button type="submit" class="btn-guardar-perfil">Guardar cambios</button>
        </form>
    </div>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Zava-php/php/componentes/footer.php'; ?>