<?php
session_start();
// Usar ruta absoluta para incluir el archivo de conexión
include_once($_SERVER['DOCUMENT_ROOT'] . '/Zava/php/componentes/conexion.php');

// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id'])) {
    echo '<div class="sin-favoritos-dinamico">Debes iniciar sesión para ver tus favoritos.</div>';
    exit;
}

$id_usuario = $_SESSION['id'];
// Obtener el tipo de favorito de GET o POST
$tipo = $_GET['tipo'] ?? $_POST['tipo'] ?? '';

if (empty($tipo)) {
    echo '<div class="sin-favoritos-dinamico">Tipo de favorito no especificado.</div>';
    exit;
}

switch($tipo) {
    case 'productos':
        // Consultar productos favoritos
        $query = "SELECT p.*, fp.fecha_agregado, u.nombre as vendedor_nombre 
                  FROM favoritos_productos fp 
                  JOIN productos p ON fp.id_producto = p.id_producto 
                  JOIN usuarios u ON p.id_usuario = u.id_usuario 
                  WHERE fp.id_usuario = ? AND p.activo = 1 
                  ORDER BY fp.fecha_agregado DESC";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_usuario);
        mysqli_stmt_execute($stmt);
        $favoritos = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($favoritos) > 0) {
            while($item = mysqli_fetch_assoc($favoritos)) {
                echo '<div class="item-favorito-dinamico">
                        <div class="imagen-favorito-dinamico">
                            <img src="/Zava/img/productos/' . htmlspecialchars($item['imagen_principal']) . '" 
                                 alt="' . htmlspecialchars($item['nombre']) . '" 
                                 onerror="this.src=\'/Zava/img/productos/producto_default.png\'">
                        </div>
                        <div class="info-favorito-dinamico">
                            <h4>' . htmlspecialchars($item['nombre']) . '</h4>
                            <p>Por: ' . htmlspecialchars($item['vendedor_nombre']) . '</p>
                            <p class="precio-dinamico">$' . number_format($item['precio'], 2) . '</p>
                            <p>Agregado: ' . date('d/m/Y', strtotime($item['fecha_agregado'])) . '</p>
                            <div class="acciones-dinamico">
                                <a href="/Zava/php/cliente/mostrarProducto.php?id=' . $item['id_producto'] . '" class="btn-ver-dinamico">Ver Producto</a>
                                <button onclick="eliminarFavoritoDinamico(\'producto\', ' . $item['id_producto'] . ')" class="btn-eliminar-dinamico">♥</button>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="sin-favoritos-dinamico">
                    No tienes productos favoritos aún. 
                    <a href="/Zava/php/cliente/productos.php">Explorar productos</a>
                  </div>';
        }
        break;
        
    case 'recetas':
        // Consultar recetas favoritas
        $query = "SELECT r.*, fr.fecha_agregado, u.nombre as autor_nombre 
                  FROM favoritos_recetas fr 
                  JOIN recetas r ON fr.id_receta = r.id_receta 
                  JOIN usuarios u ON r.id_usuario = u.id_usuario 
                  WHERE fr.id_usuario = ? AND r.activa = 1 
                  ORDER BY fr.fecha_agregado DESC";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_usuario);
        mysqli_stmt_execute($stmt);
        $favoritos = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($favoritos) > 0) {
            while($item = mysqli_fetch_assoc($favoritos)) {
                echo '<div class="item-favorito-dinamico">
                        <div class="imagen-favorito-dinamico">
                            <img src="/Zava/img/recetas/' . htmlspecialchars($item['imagen_principal']) . '" 
                                 alt="' . htmlspecialchars($item['nombre']) . '" 
                                 onerror="this.src=\'/Zava/img/recetas/receta_default.png\'">
                        </div>
                        <div class="info-favorito-dinamico">
                            <h4>' . htmlspecialchars($item['nombre']) . '</h4>
                            <p>Por: ' . htmlspecialchars($item['autor_nombre']) . '</p>
                            <p>Tipo: ' . htmlspecialchars($item['tipo_comida']) . '</p>
                            <p>Dificultad: ' . htmlspecialchars($item['dificultad']) . '</p>
                            <p>Agregado: ' . date('d/m/Y', strtotime($item['fecha_agregado'])) . '</p>
                            <div class="acciones-dinamico">
                                <a href="/Zava/php/cliente/mostrarReceta.php?id_receta=' . $item['id_receta'] . '" class="btn-ver-dinamico">Ver Receta</a>
                                <button onclick="eliminarFavoritoDinamico(\'receta\', ' . $item['id_receta'] . ')" class="btn-eliminar-dinamico">♥</button>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<div class="sin-favoritos-dinamico">
                    No tienes recetas favoritas aún. 
                    <a href="/Zava/php/cliente/recetario.php">Explorar recetas</a>
                  </div>';
        }
        break;
        
    default:
        echo '<div class="sin-favoritos-dinamico">Tipo de favorito no válido.</div>';
        break;
}
?>
