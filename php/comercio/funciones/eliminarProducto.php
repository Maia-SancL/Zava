<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['mensaje'] = 'Debes iniciar sesión para eliminar productos.';
    $_SESSION['mensaje_tipo'] = 'error';
    header('Location: inicioSesion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $id_usuario = $_SESSION['id'];


    $sql_select = "SELECT imagen_principal FROM Productos WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
    $resultado_select = mysqli_query($conexion, $sql_select);

    if ($producto = mysqli_fetch_assoc($resultado_select)) {
    
        $sql_delete = "DELETE FROM Productos WHERE id_producto = $id_producto AND id_usuario = $id_usuario";

        if (mysqli_query($conexion, $sql_delete)) {
  
            $imagen_a_eliminar = $producto['imagen_principal'];
            if ($imagen_a_eliminar && $imagen_a_eliminar !== 'producto_default.png') {
                $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/Zava/img/productos/' . $imagen_a_eliminar;
                if (file_exists($ruta_imagen)) {
                    unlink($ruta_imagen);
                }
            }
            $_SESSION['mensaje'] = 'Producto eliminado con éxito.';
            $_SESSION['mensaje_tipo'] = 'exito';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el producto de la base de datos.';
            $_SESSION['mensaje_tipo'] = 'error';
        }
    } else {
        
        $_SESSION['mensaje'] = 'No tienes permiso para eliminar este producto.';
        $_SESSION['mensaje_tipo'] = 'error';
    }
} else {
    $_SESSION['mensaje'] = 'Solicitud no válida.';
    $_SESSION['mensaje_tipo'] = 'error';
}

header('Location: panelComercio.php');
exit();
?>
