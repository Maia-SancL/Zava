<?php
session_start();
include_once('conexion.php');
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
    exit;
}

$id_usuario = $_SESSION['id'];
$tipo = $_POST['tipo'] ?? '';
$id = intval($_POST['id'] ?? 0);

if (empty($tipo) || $id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$query = null;
switch($tipo) {
    case 'producto':
        $query = "INSERT IGNORE INTO Favoritos_Productos (id_producto, id_usuario) VALUES (?, ?)";
        break;
    case 'receta':
        $query = "INSERT IGNORE INTO Favoritos_Recetas (id_receta, id_usuario) VALUES (?, ?)";
        break;
    case 'restaurante':
        $query = "INSERT IGNORE INTO Favoritos_Restaurantes (id_restaurante, id_usuario) VALUES (?, ?)";
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Tipo de favorito no válido']);
        exit;
}

if ($query) {
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id, $id_usuario);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Favorito agregado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el favorito']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error interno']);
}
?>
