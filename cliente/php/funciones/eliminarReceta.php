<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');

if (!isset($_SESSION['id'])) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'No autorizado.'];
    header('Location: /Zava/php/login.php');
    exit;
}

$id_usuario = $_SESSION['id'];
$id_receta = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id_receta <= 0) {
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'ID de receta inválido.'];
    header('Location: /Zava/php/cliente/perfil/perfilRecetas.php');
    exit;
}

$id_usuario_esc = mysqli_real_escape_string($conexion, $id_usuario);
$id_receta_esc = mysqli_real_escape_string($conexion, $id_receta);

mysqli_begin_transaction($conexion);

try {
    $query = "SELECT imagen_principal FROM Recetas WHERE id_receta = '{$id_receta_esc}' AND id_usuario = '{$id_usuario_esc}'";
    $result = mysqli_query($conexion, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        throw new Exception('No tienes permiso para eliminar esta receta o la receta no existe.');
    }
    $row = mysqli_fetch_assoc($result);
    $imagen_principal = $row['imagen_principal'];

    $galeria_query = "SELECT ruta_imagen FROM Receta_Imagenes WHERE id_receta = '{$id_receta_esc}'";
    $galeria_result = mysqli_query($conexion, $galeria_query);
    while ($img = mysqli_fetch_assoc($galeria_result)) {
        $img_path = $_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$img['ruta_imagen'];
        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }

    if ($imagen_principal && file_exists($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal)) {
        unlink($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal);
    }

    $tablas_a_limpiar = ['Receta_Imagenes', 'Comentarios_Recetas', 'Favoritos_Recetas', 'Recetas'];
    foreach ($tablas_a_limpiar as $tabla) {
        $delete_query = "DELETE FROM {$tabla} WHERE id_receta = '{$id_receta_esc}'";
        if ($tabla === 'Recetas') {
            $delete_query .= " AND id_usuario = '{$id_usuario_esc}'";
        }
        if (!mysqli_query($conexion, $delete_query)) {
            throw new Exception("Error al eliminar de la tabla {$tabla}: " . mysqli_error($conexion));
        }
    }

    mysqli_commit($conexion);
    $_SESSION['mensaje'] = ['tipo' => 'exito', 'texto' => 'Receta eliminada correctamente.'];

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => $e->getMessage()];
}

header('Location: /Zava/php/cliente/perfil/perfilRecetas.php');
exit;
