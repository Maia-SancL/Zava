<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_baneo']) && isset($_POST['id_usuario'])) {
    $id_baneo = intval($_POST['id_baneo']);
    $id_usuario = intval($_POST['id_usuario']);

    mysqli_begin_transaction($conexion);

    try {
        $consulta_desbanear = "DELETE FROM Usuarios_Baneados WHERE id_baneo = ?";
        $sentencia_desbanear = mysqli_prepare($conexion, $consulta_desbanear);
        mysqli_stmt_bind_param($sentencia_desbanear, 'i', $id_baneo);
        mysqli_stmt_execute($sentencia_desbanear);

        $consulta_activar = "UPDATE Usuarios SET activo = 1 WHERE id_usuario = ?";
        $sentencia_activar = mysqli_prepare($conexion, $consulta_activar);
        mysqli_stmt_bind_param($sentencia_activar, 'i', $id_usuario);
        mysqli_stmt_execute($sentencia_activar);

        mysqli_commit($conexion);

        header('Location: /Zava/php/admin/index_admin.php?tabla_seleccionada=usuarios_baneados');

    } catch (mysqli_sql_exception $excepcion) {
        mysqli_rollback($conexion);
        echo "Error al quitar el baneo al usuario: " . $excepcion->getMessage();
    }

} else {
    header('Location: /Zava/php/admin/index_admin.php?tabla_seleccionada=usuarios_baneados');
}
exit;
?>
