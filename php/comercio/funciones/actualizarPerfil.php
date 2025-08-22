<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/php/comercio/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $nickname = mysqli_real_escape_string($conexion, $_POST['nickname']);

    
    $sql_foto_actual = "SELECT foto FROM usuarios WHERE id_usuario = $id_usuario";
    $res_foto_actual = mysqli_query($conexion, $sql_foto_actual);
    $usuario_actual = mysqli_fetch_assoc($res_foto_actual);
    $foto_actual = $usuario_actual['foto'];

    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $directorio_subida = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/';
        $nombre_archivo = 'perfil_' . $id_usuario . '_' . time() . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $ruta_subida = $directorio_subida . $nombre_archivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_subida)) {
           
            if ($foto_actual && $foto_actual !== 'perfil.png') {
                $ruta_foto_anterior = $directorio_subida . $foto_actual;
                if (file_exists($ruta_foto_anterior)) {
                    unlink($ruta_foto_anterior);
                }
            }
            $foto_nueva = $nombre_archivo;
        } else {
            $foto_nueva = $foto_actual; 
        }
    } else {
        $foto_nueva = $foto_actual; 
    }

    $foto_nueva_escaped = mysqli_real_escape_string($conexion, $foto_nueva);

    $sql_update = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', nickname = '$nickname', foto = '$foto_nueva_escaped' WHERE id_usuario = $id_usuario";

    if (mysqli_query($conexion, $sql_update)) {
        $_SESSION['mensaje'] = 'Perfil actualizado con éxito.';
        $_SESSION['mensaje_tipo'] = 'exito';
    } else {
        $_SESSION['mensaje'] = 'Error al actualizar el perfil: ' . mysqli_error($conexion);
        $_SESSION['mensaje_tipo'] = 'error';
    }
} else {
    $_SESSION['mensaje'] = 'Solicitud no válida.';
    $_SESSION['mensaje_tipo'] = 'error';
}

header('Location: /Zava/php/comercio/perfil/perfil.php');
exit();
?>
