<?php
session_start();
include 'conexion.php';

// verificar sesion de usuario y que el carrito no este vacio
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrito'])) {
    header('Location: /Zava/php/cliente/carrito.php');
    exit();
}

// obtener datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$calle = mysqli_real_escape_string($conexion, $_POST['calle'] ?? '');
$numero = mysqli_real_escape_string($conexion, $_POST['numero'] ?? '');
$piso = mysqli_real_escape_string($conexion, $_POST['piso'] ?? '');
$departamento = mysqli_real_escape_string($conexion, $_POST['departamento'] ?? '');
$instrucciones = mysqli_real_escape_string($conexion, $_POST['instrucciones'] ?? '');

// juntar detalles de la direccion
$detalles_entrega = "Piso: $piso, Depto: $departamento. Instrucciones: $instrucciones";

// iniciar transaccion para asegurar que todo se guarde junto
mysqli_begin_transaction($conexion);

try {
    // guardar la direccion de entrega
    $ciudad = 'Ciudad Ejemplo'; // ciudad por defecto
    $sql_direccion = "INSERT INTO Datos_Entrega (id_usuario, calle, numero, piso, ciudad, detalle_extra) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_direccion = mysqli_prepare($conexion, $sql_direccion);
    mysqli_stmt_bind_param($stmt_direccion, 'isssss', $id_usuario, $calle, $numero, $piso, $ciudad, $detalles_entrega);
    mysqli_stmt_execute($stmt_direccion);
    $id_direccion = mysqli_insert_id($conexion);

    if ($id_direccion == 0) {
        throw new Exception("error al guardar la direccion.");
    }

    // calcular total del carrito para seguridad
    $productos_carrito = [];
    $total_carrito = 0;
    $ids_productos = implode(',', array_keys($_SESSION['carrito']));
    $sql_productos = "SELECT id_producto, precio FROM Productos WHERE id_producto IN ($ids_productos)";
    $resultado = mysqli_query($conexion, $sql_productos);

    while ($producto = mysqli_fetch_assoc($resultado)) {
        $id_producto = $producto['id_producto'];
        $cantidad = $_SESSION['carrito'][$id_producto];
        $subtotal = $producto['precio'] * $cantidad;

        $productos_carrito[] = [
            'id' => $id_producto,
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
        $total_carrito += $subtotal;
    }
    
    $costo_envio = 2000; // costo de envio fijo
    $total_final = $total_carrito + $costo_envio;

    // crear el pedido
    $numero_pedido = uniqid('ZAVA-');
    $sql_pedido = "INSERT INTO Pedidos (id_usuario, numero_pedido, estado, subtotal, total, id_datos_entrega, tipo_entrega) VALUES (?, ?, 'confirmado', ?, ?, ?, 'domicilio')";
    $stmt_pedido = mysqli_prepare($conexion, $sql_pedido);
    mysqli_stmt_bind_param($stmt_pedido, 'isddi', $id_usuario, $numero_pedido, $total_carrito, $total_final, $id_direccion);
    mysqli_stmt_execute($stmt_pedido);
    $id_pedido = mysqli_insert_id($conexion);

    if ($id_pedido == 0) {
        throw new Exception("error al crear el pedido.");
    }

    // guardar los productos del pedido
    $sql_detalle_pedido = "INSERT INTO Detalle_Pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_detalle_pedido = mysqli_prepare($conexion, $sql_detalle_pedido);

    foreach ($productos_carrito as $producto_carrito) {
        mysqli_stmt_bind_param($stmt_detalle_pedido, 'iiidd', $id_pedido, $producto_carrito['id'], $producto_carrito['cantidad'], $producto_carrito['precio'], $producto_carrito['subtotal']);
        mysqli_stmt_execute($stmt_detalle_pedido);
    }

    // si todo ok, confirmar cambios en la base de datos
    mysqli_commit($conexion);

    // vaciar carrito y redirigir a la pagina de confirmacion
    unset($_SESSION['carrito']);
    header('Location: /Zava/php/cliente/confirmacion.php?pedido=' . $numero_pedido);
    exit();

} catch (Exception $error) {
    // si hay un error, deshacer todos los cambios
    mysqli_rollback($conexion);
    
    // mostrar error
    echo "hubo un error al procesar tu pedido: " . $error->getMessage();
    // aqui podrias redirigir a una pagina de error
}

?>
