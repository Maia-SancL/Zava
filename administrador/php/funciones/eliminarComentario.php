<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comentario'])) {
    $id_comentario = intval($_POST['id_comentario']);

    $consulta = "DELETE FROM Comentarios_Recetas WHERE id_comentario = ?";
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, 'i', $id_comentario);

    if (mysqli_stmt_execute($sentencia)) {
        header('Location: ' . BASE_URL . 'administrador/panel/comentarios');
    } else {
        echo "Error al eliminar el comentario.";
    }
} else {
    header('Location: ' . BASE_URL . 'administrador/panel/comentarios');
}
exit;
?>
