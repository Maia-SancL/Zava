<?php

session_start();
include_once('./php/componentes/header.php');
include_once('./php/componentes/navegador.php');
include_once('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

// Lista de países y banderas (puedes agregar más si lo deseas)
$paises = [
    "Argentina" => "🇦🇷",
    "Brasil" => "🇧🇷",
    "Chile" => "🇨🇱",
    "Uruguay" => "🇺🇾",
    "Paraguay" => "🇵🇾",
    "México" => "🇲🇽",
    "España" => "🇪🇸",
    "Estados Unidos" => "🇺🇸",
    "Colombia" => "🇨🇴",
    "Perú" => "🇵🇪"
];

// Obtener datos actuales del usuario
$query_usuario = "SELECT nombre, apellido, nickname, foto, ubicacion FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $nickname = mysqli_real_escape_string($conexion, $_POST['nickname']);
    $ubicacion = mysqli_real_escape_string($conexion, $_POST['ubicacion']);

    // Manejo de foto de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nombre = uniqid('perfil_') . '_' . basename($_FILES['foto']['name']);
        $ruta_destino = "profile_uploads/" . $foto_nombre;
        move_uploaded_file($foto_tmp, $ruta_destino);
        $foto_sql = ", foto = '$ruta_destino'";
    } else {
        $foto_sql = "";
    }

    $query_update = "UPDATE usuarios SET 
        nombre = '$nombre',
        apellido = '$apellido',
        nickname = '$nickname',
        ubicacion = '$ubicacion'
        $foto_sql
        WHERE id_usuario = $id_usuario";
    if (mysqli_query($conexion, $query_update)) {
        $mensaje = "Perfil actualizado correctamente.";
        // Refrescar datos
        $resultado_usuario = mysqli_query($conexion, $query_usuario);
        $usuario = mysqli_fetch_assoc($resultado_usuario);
    } else {
        $mensaje = "Error al actualizar el perfil.";
    }
}

$nombre = htmlspecialchars($usuario['nombre']);
$apellido = htmlspecialchars($usuario['apellido']);
$nickname = htmlspecialchars($usuario['nickname']);
$ubicacion = htmlspecialchars($usuario['ubicacion'] ?? '');
$foto = $usuario['foto'] ? htmlspecialchars($usuario['foto']) : 'perfil.png';
?>

<!-- Información del usuario -->
<div class="editar-perfil-container">
    <h2>Editar perfil</h2>
    <?php if ($mensaje): ?>
        <div class="mensaje-perfil"><?= $mensaje ?></div>
    <?php endif; ?>
    <form class="form-editar-perfil" method="POST" enctype="multipart/form-data">

        <!--FOTO DE PERFIL-->
        <label for="foto">Foto de perfil</label>
        <img src="<?= $foto ?>" alt="Foto de perfil" class="foto-preview">
        <input type="file" name="foto" id="foto" accept="image/*">

        <!--NOMBRE-->
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>" required>

        <!--APELLIDO-->
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" value="<?= $apellido ?>" required>

        <!--NICKNAME-->
        <label for="nickname">Usuario</label>
        <input type="text" name="nickname" id="nickname" value="<?= $nickname ?>" required>

        <!--UBICACION-->
        <label for="ubicacion">Ubicación</label>
        <select name="ubicacion" id="ubicacion" required onchange="mostrarBandera()">
            <option value="">Selecciona un país</option>
            <?php foreach ($paises as $pais => $bandera): ?>
                <option value="<?= $pais ?>" <?= $ubicacion === $pais ? 'selected' : '' ?>>
                    <?= $pais ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div id="pais-preview" class="pais-preview" style="<?= $ubicacion ? '' : 'display:none;' ?>">
            <span class="pais-bandera" id="bandera-preview">
                <?= $ubicacion && isset($paises[$ubicacion]) ? $paises[$ubicacion] : '' ?>
            </span>
            <span id="nombre-pais-preview"><?= $ubicacion ?></span>
        </div>

        <button type="submit">Guardar cambios</button>
    </form>
</div>
<script>
    const paises = <?php echo json_encode($paises); ?>;
    function mostrarBandera() {
        const select = document.getElementById('ubicacion');
        const preview = document.getElementById('pais-preview');
        const bandera = document.getElementById('bandera-preview');
        const nombre = document.getElementById('nombre-pais-preview');
        const pais = select.value;
        if (pais && paises[pais]) {
            bandera.textContent = paises[pais];
            nombre.textContent = pais;
            preview.style.display = 'flex';
        } else {
            preview.style.display = 'none';
        }
    }
</script>