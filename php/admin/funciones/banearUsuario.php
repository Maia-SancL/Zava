<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $motivo = "Baneado por el administrador.";

    mysqli_begin_transaction($conexion);

    try {
        $consulta_banear = "INSERT INTO Usuarios_Baneados (id_usuario, motivo) VALUES (?, ?)";
        $sentencia_banear = mysqli_prepare($conexion, $consulta_banear);
        mysqli_stmt_bind_param($sentencia_banear, 'is', $id_usuario, $motivo);
        mysqli_stmt_execute($sentencia_banear);

        $consulta_desactivar = "UPDATE Usuarios SET activo = 0 WHERE id_usuario = ?";
        $sentencia_desactivar = mysqli_prepare($conexion, $consulta_desactivar);
        mysqli_stmt_bind_param($sentencia_desactivar, 'i', $id_usuario);
        mysqli_stmt_execute($sentencia_desactivar);

        mysqli_commit($conexion);

        header('Location: /Zava/php/admin/index_admin.php?tabla_seleccionada=usuarios');

    } catch (mysqli_sql_exception $excepcion) {
        mysqli_rollback($conexion);
        echo "Error al banear al usuario: " . $excepcion->getMessage();
    }

} else {
    header('Location: /Zava/php/admin/index_admin.php?tabla_seleccionada=usuarios');
}
exit;
?>
