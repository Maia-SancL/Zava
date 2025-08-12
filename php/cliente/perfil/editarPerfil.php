<?php
session_start();
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    header("Location: /Zava/php/cliente/login.php");
    exit;
}

$id_usuario = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $nickname = mysqli_real_escape_string($conexion, $_POST['nickname']);

    $query_foto_update = "";
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $directorio_destino = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/';
        $nombre_archivo_seguro = uniqid() . '_' . basename(preg_replace("/[^a-zA-Z0-9._-]+", "", $_FILES['foto']['name']));
        $ruta_completa = $directorio_destino . $nombre_archivo_seguro;
        
        $tipo_archivo = strtolower(pathinfo($ruta_completa, PATHINFO_EXTENSION));
        $permitidos = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($tipo_archivo, $permitidos)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_completa)) {
                $query_foto_update = ", foto = '{$nombre_archivo_seguro}'";
            }
        }
    }

    $query_update = "UPDATE usuarios SET 
                        nombre = '{$nombre}', 
                        apellido = '{$apellido}', 
                        nickname = '{$nickname}'
                        {$query_foto_update}
                     WHERE id_usuario = {$id_usuario}";

    if (mysqli_query($conexion, $query_update)) {
        header("Location: perfil.php");
        exit;
    } else {
        echo "Error al actualizar el perfil: " . mysqli_error($conexion);
    }
} else {
    header("Location: perfil.php");
    exit;
}
?>
