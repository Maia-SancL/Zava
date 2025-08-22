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
        $query = "DELETE FROM favoritos_productos WHERE id_producto = ? AND id_usuario = ?";
        break;
    case 'receta':
        $query = "DELETE FROM favoritos_recetas WHERE id_receta = ? AND id_usuario = ?";
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Tipo de favorito no válido']);
        exit;
}

if ($query) {
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id, $id_usuario);
    
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        if ($affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Favorito eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el favorito o ya fue eliminado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el favorito']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error interno']);
}
?>
