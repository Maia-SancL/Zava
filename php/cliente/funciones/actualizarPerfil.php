<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');

if (!isset($_SESSION['id']) || !isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    header('Location: /Zava/login.php');
    exit();
}

$id_usuario = $_SESSION['id'];
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$nickname = $_POST['nickname'] ?? '';

$nombre_esc = mysqli_real_escape_string($conexion, $nombre);
$apellido_esc = mysqli_real_escape_string($conexion, $apellido);
$nickname_esc = mysqli_real_escape_string($conexion, $nickname);

$query_update_texto = "UPDATE usuarios SET nombre = '$nombre_esc', apellido = '$apellido_esc', nickname = '$nickname_esc' WHERE id_usuario = $id_usuario";
mysqli_query($conexion, $query_update_texto);

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $query_foto_actual = "SELECT foto FROM usuarios WHERE id_usuario = $id_usuario";
    $resultado_foto = mysqli_query($conexion, $query_foto_actual);
    $usuario_actual = mysqli_fetch_assoc($resultado_foto);

    if ($usuario_actual && $usuario_actual['foto'] && $usuario_actual['foto'] !== 'perfil.png') {
        $ruta_foto_anterior = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/' . $usuario_actual['foto'];
        if (file_exists($ruta_foto_anterior)) {
            unlink($ruta_foto_anterior);
        }
    }

    $nombre_foto = time() . '_' . basename($_FILES["foto"]["name"]);
    $directorio_subida = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/';
    $fichero_subido = $directorio_subida . $nombre_foto;
    $nombre_foto_esc = mysqli_real_escape_string($conexion, $nombre_foto);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $fichero_subido)) {
        $query_update_foto = "UPDATE usuarios SET foto = '$nombre_foto_esc' WHERE id_usuario = $id_usuario";
        mysqli_query($conexion, $query_update_foto);
    } else {
        $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error al subir la imagen.'];
        header('Location: /Zava/php/cliente/perfil/perfil.php');
        exit();
    }
}

$_SESSION['mensaje'] = ['tipo' => 'exito', 'texto' => 'Perfil actualizado correctamente.'];
header('Location: /Zava/php/cliente/perfil/perfil.php');
exit();
?>
