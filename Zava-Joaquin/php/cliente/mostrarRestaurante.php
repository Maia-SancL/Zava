<?php
include_once 'php/componentes/header.php';
include_once('conexion.php');

// Obtiene el ID del restaurante desde el formulario enviado por POST
$id_restaurante = $_POST['id_restaurante'];

// Consulta para obtener los datos del restaurante
$query_restaurante = "SELECT * FROM restaurantes WHERE id_restaurante = $id_restaurante";
$resultado_restaurante = mysqli_query($conexion, $query_restaurante);
$restaurante = mysqli_fetch_array($resultado_restaurante);

// Consulta para obtener los datos del usuario que registró el restaurante
$id_usuario = $restaurante['id_usuario'];
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

// Datos del restaurante
$nombre_restaurante = $restaurante['nombre'];
$descripcion_restaurante = $restaurante['descripcion'];
$direccion_restaurante = $restaurante['direccion'];
$imagen_restaurante = $restaurante['imagen'] ? $restaurante['imagen'] : 'restaurante.png';
$promedio_calificacion = $restaurante['promedio_calificacion'];

// Datos del usuario
$nombre_usuario = $usuario['nombre'];
$apellido_usuario = $usuario['apellido'];
$nickname_usuario = $usuario['nickname'];
$foto_usuario = $usuario['foto'] ? $usuario['foto'] : 'perfil.png';
?>

<!-- Mostrar información del restaurante -->
<div class="restaurante">
    <h1 class="titulo-restaurante"><?php echo $nombre_restaurante; ?></h1>
    <img src="<?php echo $imagen_restaurante; ?>" alt="Imagen del restaurante" class="imagen-restaurante">
    <p class="descripcion-restaurante"><?php echo $descripcion_restaurante; ?></p>
    <p class="direccion-restaurante">Dirección: <?php echo $direccion_restaurante; ?></p>
    <p class="calificacion-restaurante">Calificación promedio: <?php echo $promedio_calificacion; ?></p>
</div>

<!-- Mostrar información del usuario que registró el restaurante -->
<div class="usuario-restaurante">
    <img src="<?php echo $foto_usuario; ?>" alt="Foto de perfil" class="foto-perfil">
    <p class="nombre-usuario"><?php echo $nombre_usuario . ' ' . $apellido_usuario; ?></p>
    <p class="nickname">@<?php echo $nickname_usuario; ?></p>
</div>
