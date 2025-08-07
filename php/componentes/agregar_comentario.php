<?php
session_start();
include_once('conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];
$comentario = trim($_POST['comentario'] ?? '');
$tipo = $_POST['tipo'] ?? '';
$id_contenido = intval($_POST['id'] ?? 0);

if (empty($comentario) || empty($tipo) || $id_contenido <= 0) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

$tabla = '';
$columna_id = '';

if ($tipo === 'receta') {
    $tabla = 'Comentarios_Recetas';
    $columna_id = 'id_receta';
} elseif ($tipo === 'restaurante') {
    $tabla = 'Comentarios_Restaurantes';
    $columna_id = 'id_restaurante';
} else {
    echo json_encode(['success' => false, 'error' => 'Tipo de contenido no válido']);
    exit;
}

$sql = "INSERT INTO $tabla (id_usuario, $columna_id, comentario) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("iis", $id_usuario, $id_contenido, $comentario);

if ($stmt->execute()) {
    $nuevo_comentario = [
        'id_comentario' => $stmt->insert_id,
        'comentario' => htmlspecialchars($comentario),
        'fecha_comentario' => date('d/m/Y'),
        'nombre' => htmlspecialchars($_SESSION['usuario']['nombre']),
        'nickname' => htmlspecialchars($_SESSION['usuario']['nickname']),
        'foto' => htmlspecialchars($_SESSION['usuario']['foto'] ?? 'default.png')
    ];
    echo json_encode(['success' => true, 'comentario' => $nuevo_comentario]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al guardar el comentario: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
