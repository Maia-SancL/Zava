<?php
session_start();
include_once('perfil/conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$id_usuario = $_SESSION['id'];
$id_receta = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id_receta <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de receta inválido.']);
    exit;
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Verificar que la receta pertenece al usuario
    $query = "SELECT imagen_principal FROM Recetas WHERE id_receta = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id_receta, $id_usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        throw new Exception('No tienes permiso para eliminar esta receta.');
    }
    $row = mysqli_fetch_assoc($result);
    $imagen_principal = $row['imagen_principal'];

    // Eliminar imágenes de galería
    $galeria_query = "SELECT ruta_imagen FROM Receta_Imagenes WHERE id_receta = ?";
    $stmt_galeria = mysqli_prepare($conexion, $galeria_query);
    mysqli_stmt_bind_param($stmt_galeria, "i", $id_receta);
    mysqli_stmt_execute($stmt_galeria);
    $galeria_result = mysqli_stmt_get_result($stmt_galeria);
    while ($img = mysqli_fetch_assoc($galeria_result)) {
        $img_path = $_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$img['ruta_imagen'];
        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }

    // Eliminar imagen principal
    if ($imagen_principal && file_exists($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal)) {
        unlink($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal);
    }

    // Eliminar registros de la base de datos
    $tablas_a_limpiar = ['Receta_Imagenes', 'Comentarios_Recetas', 'Favoritos_Recetas', 'Recetas'];
    foreach ($tablas_a_limpiar as $tabla) {
        $delete_query = "DELETE FROM $tabla WHERE id_receta = ?";
        if ($tabla === 'Recetas') {
            $delete_query .= " AND id_usuario = ?";
        }
        $stmt_delete = mysqli_prepare($conexion, $delete_query);
        if ($tabla === 'Recetas') {
            mysqli_stmt_bind_param($stmt_delete, "ii", $id_receta, $id_usuario);
        } else {
            mysqli_stmt_bind_param($stmt_delete, "i", $id_receta);
        }
        mysqli_stmt_execute($stmt_delete);
    }

    // Confirmar transacción
    mysqli_commit($conexion);
    echo json_encode(['success' => true, 'message' => 'Receta eliminada correctamente.']);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

exit;
