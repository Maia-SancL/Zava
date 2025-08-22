<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/php/cliente/login.php');
    exit();
}

$id_usuario = $_SESSION['id'];

mysqli_begin_transaction($conexion);

try {
    $tablas_relacionadas = ['favoritos_productos', 'favoritos_recetas', 'pedidos', 'opiniones', 'recetas'];
    foreach ($tablas_relacionadas as $tabla) {
        $sql_delete_relacionados = "DELETE FROM $tabla WHERE id_usuario = $id_usuario";
        if (!mysqli_query($conexion, $sql_delete_relacionados)) {
            if (mysqli_errno($conexion) !== 1146) { 
                 throw new Exception("Error al eliminar datos de la tabla '$tabla'.");
            }
        }
    }

    $sql_usuario = "SELECT foto FROM usuarios WHERE id_usuario = $id_usuario";
    $res_usuario = mysqli_query($conexion, $sql_usuario);
    if ($res_usuario && mysqli_num_rows($res_usuario) > 0) {
        $usuario = mysqli_fetch_assoc($res_usuario);
        if ($usuario['foto'] && $usuario['foto'] !== 'perfil.png') {
            $ruta_foto_perfil = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/perfiles/' . $usuario['foto'];
            if (file_exists($ruta_foto_perfil)) {
                unlink($ruta_foto_perfil);
            }
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
    header('Location: /Zava/php/cliente/perfil/perfil.php');
    exit();
}
?>
