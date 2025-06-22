<?php
include_once 'php/componentes/header.php';
include_once('conexion.php');

// Obtiene el ID del producto desde el formulario enviado por POST
$id_producto = $_POST['id_producto'];

// Consulta para obtener los datos del producto
$query_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
$resultado_producto = mysqli_query($conexion, $query_producto);
$producto = mysqli_fetch_array($resultado_producto);

// Consulta para obtener los datos del usuario que registró el producto
$id_usuario = $producto['id_usuario'];
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

// Datos del producto
$nombre_producto = $producto['nombre'];
$descripcion_producto = $producto['descripcion'];
$precio_producto = $producto['precio'];
$stock_producto = $producto['stock'];
$imagen_producto = $producto['imagen'] ? $producto['imagen'] : 'default.png';

// Datos del usuario
$nombre_usuario = $usuario['nombre'];
$apellido_usuario = $usuario['apellido'];
$nickname_usuario = $usuario['nickname'];
$foto_usuario = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';
?>

<!-- Mostrar información del producto -->
<div class="producto">
    <h1 class="titulo-producto"><?php echo $nombre_producto; ?></h1>
    <img src="<?php echo $imagen_producto; ?>" alt="Imagen del producto" class="imagen-producto">
    <p class="descripcion-producto"><?php echo $descripcion_producto; ?></p>
    <p class="precio-producto">Precio: $<?php echo $precio_producto; ?></p>
    <p class="stock-producto">Stock disponible: <?php echo $stock_producto; ?></p>
</div>

<!-- Mostrar información del usuario que registró el producto -->
<div class="usuario-producto">
    <img src="<?php echo $foto_usuario; ?>" alt="Foto de perfil" class="foto-perfil">
    <p class="nombre-usuario"><?php echo $nombre_usuario . ' ' . $apellido_usuario; ?></p>
    <p class="nickname">@<?php echo $nickname_usuario; ?></p>
</div>
