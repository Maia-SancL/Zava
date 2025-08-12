<?php
session_start();
include_once('perfil/conexion.php');

if (!isset($_SESSION['id'])) {
    header('Location: /Zava/php/cliente/login.php');
    exit;
}

$id_usuario = $_SESSION['id'];
$id_receta = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_receta <= 0) {
    header('Location: perfilRecetas.php?error=ID inválido');
    exit;
}

// Verificar que la receta pertenece al usuario
$query = "SELECT imagen_principal FROM Recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario";
$result = mysqli_query($conexion, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: perfilRecetas.php?error=No autorizado');
    exit;
}
$row = mysqli_fetch_assoc($result);
$imagen_principal = $row['imagen_principal'];

// Eliminar imágenes asociadas (principal y galería)
if ($imagen_principal && file_exists($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal)) {
    unlink($_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$imagen_principal);
}

// Eliminar imágenes de galería
$galeria = mysqli_query($conexion, "SELECT ruta_imagen FROM Receta_Imagenes WHERE id_receta = $id_receta");
while ($img = mysqli_fetch_assoc($galeria)) {
    $img_path = $_SERVER['DOCUMENT_ROOT'].'/Zava/img/recetas/'.$img['ruta_imagen'];
    if (file_exists($img_path)) {
        unlink($img_path);
    }
}

// Eliminar registros de galería
mysqli_query($conexion, "DELETE FROM Receta_Imagenes WHERE id_receta = $id_receta");
// Eliminar comentarios
mysqli_query($conexion, "DELETE FROM Comentarios_Recetas WHERE id_receta = $id_receta");
// Eliminar favoritos
mysqli_query($conexion, "DELETE FROM Favoritos_Recetas WHERE id_receta = $id_receta");
// Eliminar la receta
mysqli_query($conexion, "DELETE FROM Recetas WHERE id_receta = $id_receta AND id_usuario = $id_usuario");

header('Location: perfilRecetas.php');
exit;
