<?php
session_start();
include_once('conexion.php');

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$id_usuario = $_SESSION['id'];
$id_comentario = isset($_POST['id_comentario']) ? intval($_POST['id_comentario']) : 0;

if ($id_comentario <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$query = "UPDATE Comentarios_Recetas SET activo = 0 WHERE id_comentario = $id_comentario AND id_usuario = $id_usuario";
$result = mysqli_query($conexion, $query);

if ($result && mysqli_affected_rows($conexion) > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la opinión']);
}
