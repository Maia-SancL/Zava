<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/public/componentes/php/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);

    $consulta_estado = "SELECT activo FROM Usuarios WHERE id_usuario = ?";
    $sentencia_estado = mysqli_prepare($conexion, $consulta_estado);
    mysqli_stmt_bind_param($sentencia_estado, 'i', $id_usuario);
    mysqli_stmt_execute($sentencia_estado);
    $resultado_estado = mysqli_stmt_get_result($sentencia_estado);
    
    if ($fila = mysqli_fetch_assoc($resultado_estado)) {
        $nuevo_estado = $fila['activo'] ? 0 : 1;

        $consulta_actualizar = "UPDATE Usuarios SET activo = ? WHERE id_usuario = ?";
        $sentencia_actualizar = mysqli_prepare($conexion, $consulta_actualizar);
        mysqli_stmt_bind_param($sentencia_actualizar, 'ii', $nuevo_estado, $id_usuario);
        
        if (mysqli_stmt_execute($sentencia_actualizar)) {
            header('Location: ' . BASE_URL . 'administrador/panel/usuarios');
        } else {
            echo "Error al actualizar el estado del usuario.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    header('Location: ' . BASE_URL . 'administrador/panel/usuarios');
}
exit;
?>
