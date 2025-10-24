<?php
session_start();
include_once 'c:/xampp/htdocs/Zava/administrador/php/conexion.php';

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $id_usuario = $_SESSION['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $id_categoria = !empty($_POST['id_categoria']) ? intval($_POST['id_categoria']) : NULL;
    $activo = intval($_POST['activo']);

   
    $sql_verif = "SELECT imagen_principal FROM Productos WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
    $resultado_verif = mysqli_query($conexion, $sql_verif);

    if ($producto_actual = mysqli_fetch_assoc($resultado_verif)) {
        $imagen_actual = $producto_actual['imagen_principal'];

    
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] == 0) {
            $directorio_subida = $_SERVER['DOCUMENT_ROOT'] . '/Zava/public/img/productos/';
            $nombre_archivo = time() . '_' . basename($_FILES['imagen_principal']['name']);
            $ruta_subida = $directorio_subida . $nombre_archivo;

            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_subida)) {
              
                if ($imagen_actual && $imagen_actual !== 'producto_default.png') {
                    $ruta_imagen_anterior = $directorio_subida . $imagen_actual;
                    if (file_exists($ruta_imagen_anterior)) {
                        unlink($ruta_imagen_anterior);
                    }
                }
                $imagen_nueva = $nombre_archivo;
            } else {
                $imagen_nueva = $imagen_actual; 
            }
        } else {
            $imagen_nueva = $imagen_actual; 
        }

        
        $imagen_nueva_escaped = mysqli_real_escape_string($conexion, $imagen_nueva);
        $id_categoria_sql = is_null($id_categoria) ? "NULL" : $id_categoria;

      
        $sql_update = "UPDATE Productos SET 
                        nombre = '$nombre', 
                        descripcion = '$descripcion', 
                        precio = $precio, 
                        stock = $stock, 
                        id_categoria = $id_categoria_sql, 
                        activo = $activo, 
                        imagen_principal = '$imagen_nueva_escaped' 
                      WHERE id_producto = $id_producto AND id_usuario = $id_usuario";

        if (mysqli_query($conexion, $sql_update)) {
            $_SESSION['mensaje'] = 'Producto actualizado con éxito.';
            $_SESSION['mensaje_tipo'] = 'exito';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el producto: ' . mysqli_error($conexion);
            $_SESSION['mensaje_tipo'] = 'error';
        }
    } else {
        $_SESSION['mensaje'] = 'No tienes permiso para modificar este producto.';
        $_SESSION['mensaje_tipo'] = 'error';
    }
} else {
    $_SESSION['mensaje'] = 'Solicitud no válida.';
    $_SESSION['mensaje_tipo'] = 'error';
}

header('Location: /Zava/comercio/php/panelComercio.php');
exit();
?>
