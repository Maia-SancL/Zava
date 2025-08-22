<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/php/comercio/login.php');
    exit();
}

$id_usuario = $_SESSION['id'];

mysqli_begin_transaction($conexion);

try {
 
    $sql_productos = "SELECT imagen_principal FROM productos WHERE id_usuario = $id_usuario";
    $res_productos = mysqli_query($conexion, $sql_productos);
    $directorio_productos = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/productos/';

    while ($producto = mysqli_fetch_assoc($res_productos)) {
        if ($producto['imagen_principal'] && $producto['imagen_principal'] !== 'producto_default.png') {
            $ruta_imagen_producto = $directorio_productos . $producto['imagen_principal'];
            if (file_exists($ruta_imagen_producto)) {
                unlink($ruta_imagen_producto);
            }
        }
    }


    $sql_delete_productos = "DELETE FROM productos WHERE id_usuario = $id_usuario";
    if (!mysqli_query($conexion, $sql_delete_productos)) {
        throw new Exception('Error al eliminar los productos.');
    }


    $sql_usuario = "SELECT foto FROM usuarios WHERE id_usuario = $id_usuario";
    $res_usuario = mysqli_query($conexion, $sql_usuario);
    $usuario = mysqli_fetch_assoc($res_usuario);
    if ($usuario['foto'] && $usuario['foto'] !== 'perfil.png') {
        $ruta_foto_perfil = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/' . $usuario['foto'];
        if (file_exists($ruta_foto_perfil)) {
            unlink($ruta_foto_perfil);
        }
    }


    $sql_delete_usuario = "DELETE FROM usuarios WHERE id_usuario = $id_usuario";
    if (!mysqli_query($conexion, $sql_delete_usuario)) {
        throw new Exception('Error al eliminar la cuenta de usuario.');
    }

   
    mysqli_commit($conexion);


    session_destroy();
    header('Location: /Zava/index.php?mensaje=Cuenta eliminada con éxito');
    exit();

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['mensaje'] = 'Error al eliminar la cuenta: ' . $e->getMessage();
    $_SESSION['mensaje_tipo'] = 'error';
    header('Location: /Zava/php/comercio/perfil/perfil.php');
    exit();
}
?>
