<?php
session_start();
include_once 'c:/xampp/htdocs/Zava/administrador/php/conexion.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['mensaje'] = 'Debes iniciar sesión para realizar esta acción.';
    $_SESSION['mensaje_tipo'] = 'error';
    header('Location: /Zava/login.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_producto = intval($_POST['id_producto']);
    $cantidad_a_agregar = intval($_POST['cantidad']);
    $id_usuario = $_SESSION['id'];

    if ($cantidad_a_agregar <= 0) {
        $_SESSION['mensaje'] = 'La cantidad a agregar debe ser mayor que cero.';
        $_SESSION['mensaje_tipo'] = 'error';
        header('Location: /Zava/comercio/php/panelComercio.php');
        exit();
    }

 
    mysqli_begin_transaction($conexion);

    try {
       
        $sql_verif = "SELECT stock FROM Productos WHERE id_producto = $id_producto AND id_usuario = $id_usuario FOR UPDATE";
        $resultado_verif = mysqli_query($conexion, $sql_verif);

        if (mysqli_num_rows($resultado_verif) > 0) {
  
            $sql_update = "UPDATE Productos SET stock = stock + $cantidad_a_agregar WHERE id_producto = $id_producto";

            if (mysqli_query($conexion, $sql_update)) {
                mysqli_commit($conexion);
                $_SESSION['mensaje'] = 'Stock actualizado con éxito.';
                $_SESSION['mensaje_tipo'] = 'exito';
            } else {
                throw new Exception('Error al actualizar el stock.');
            }
        } else {
            throw new Exception('No tienes permiso para modificar este producto.');
        }
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        $_SESSION['mensaje'] = $e->getMessage();
        $_SESSION['mensaje_tipo'] = 'error';
    }

} else {
    $_SESSION['mensaje'] = 'Solicitud no válida.';
    $_SESSION['mensaje_tipo'] = 'error';
}

header('Location: /Zava/comercio/php/panelComercio.php');
exit();
?>
