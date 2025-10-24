<?php
session_start();
include_once('/Zava/php/general/conexion.php');


if (isset($_SESSION['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = intval($_SESSION['id']);
    $comentario = trim($_POST['comentario']);
    $tipo = $_POST['tipo'];
    $id_contenido = intval($_POST['id']);

   
    if (!empty($comentario) && $tipo === 'receta' && $id_contenido > 0) {
        $tabla = 'Comentarios_Recetas';
        $columna_id = 'id_receta';
        
        
        $comentario_escaped = mysqli_real_escape_string($conexion, $comentario);

      
        $sql = "INSERT INTO $tabla (id_usuario, $columna_id, comentario, fecha_comentario) VALUES ($id_usuario, $id_contenido, '$comentario_escaped', NOW())";
        mysqli_query($conexion, $sql);
    }
}


$redirect_url = '/Zava/php/cliente/mostrarReceta.php?id_receta=' . $id_contenido;
header("Location: " . $redirect_url);
exit;
?>
