<?php
session_start();
include_once('../general/conexion.php');

// Verificar si el usuario está logueado y si los datos del formulario fueron enviados
if (isset($_SESSION['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = intval($_SESSION['id']);
    $comentario = trim($_POST['comentario']);
    $tipo = $_POST['tipo'];
    $id_contenido = intval($_POST['id']);

    // Validaciones básicas
    if (!empty($comentario) && in_array($tipo, ['receta', 'restaurante']) && $id_contenido > 0) {
        $tabla = ($tipo === 'receta') ? 'Comentarios_Recetas' : 'Comentarios_Restaurantes';
        $columna_id = ($tipo === 'receta') ? 'id_receta' : 'id_restaurante';
        
        // Escapar el comentario para seguridad
        $comentario_escaped = mysqli_real_escape_string($conexion, $comentario);

        // Insertar el comentario
        $sql = "INSERT INTO $tabla (id_usuario, $columna_id, comentario, fecha_comentario) VALUES ($id_usuario, $id_contenido, '$comentario_escaped', NOW())";
        mysqli_query($conexion, $sql);
    }
}

// Redirigir de vuelta a la página de la receta
$redirect_url = '/Zava/php/cliente/mostrarReceta.php?id_receta=' . $id_contenido;
header("Location: " . $redirect_url);
exit;
?>
