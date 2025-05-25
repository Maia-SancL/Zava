<?php

// Inicia la sesión y carga los componentes necesarios
session_start();
include_once 'php/componentes/header.php';
include_once 'php/componentes/top-menu.php';
include_once 'conexion.php';

// Variable para mensajes de error o éxito
$mensaje = '';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Obtiene el ID del usuario de la sesión
$id_usuario = $_SESSION['id'];

// Si el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que los campos requeridos estén presentes
    if (
        isset($_POST['nombre']) &&
        isset($_POST['descripcion']) &&
        isset($_POST['precio']) &&
        isset($_POST['stock']) &&
        isset($_POST['peso'])
    ) {
        // Escapa los datos recibidos del formulario
        $fk_id_usuario = intval($id_usuario);
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['pasos']);
        $peso = floatval($conexion, $_POST['tipo_comida']);

        // Manejo de imágenes subidas (soporta hasta 3 imágenes)
        $imagenes = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($_FILES["imagen$i"]['name'])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["imagen$i"]['name']);
                if (move_uploaded_file($_FILES["imagen$i"]['tmp_name'], $target_file)) {
                    $imagenes[] = $target_file;
                }
            }
        }

        // Convierte el array de imágenes a JSON para guardar en la base de datos
        $imagenes_json = json_encode($imagenes);

        // Inserta el producto en la base de datos
        $query = "INSERT INTO productos (id_usuario, nombre, descripcion, precio, stock, imagen) 
          VALUES ($fk_id_usuario, '$nombre', '$descripcion', '$precio', '$stpck', '$imagenes_json')";
        $resultado = mysqli_query($conexion, $query);

        // Verifica si la inserción fue exitosa
        if ($resultado) {
            $mensaje = "Producto agregado exitosamente.";
        } else {
            $mensaje = "Error al agregar el producto.";
        }
    } else {
        // Si faltan campos requeridos
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>

<div class="agregar-producto">
    <h1>Agregar producto</h1>
    <?php if ($mensaje): ?>
        <!-- Muestra mensaje de error o éxito -->
        <p style="color:red;"><?= $mensaje ?></p>
    <?php endif; ?>
    <form action="crear.php" method="POST" enctype="multipart/form-data">
        <!-- Campo para el nombre del producto -->
        <label for="nombre">Nombre del producto:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <!-- Campo para la descripción del producto -->
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea><br>

        <!-- Sección para el precio -->
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required placeholder="0.00"><br>

        <!-- Sección para el stock -->
        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required placeholder="0.00"><br>

        <!-- Sección para el peso -->
        <label for="peso">Peso (en gramos):</label>
        <input type="number" step="0.01" name="peso" id="peso" required placeholder="0.00"><br>

        <!-- Sección para subir imágenes del producto -->
        <label>Imágenes:</label>
        <div id="zona-imagenes" class="zona-imagenes">
            <div class="icono-imagen">
                <!-- SVG de icono de imagen -->
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="#621111" stroke-width="2"/>
                    <polyline points="8 16 12 12 16 16" stroke="#621111" stroke-width="2" fill="none"/>
                    <line x1="12" y1="12" x2="12" y2="16" stroke="#621111" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke="#621111" stroke-width="2"/>
                    <circle cx="12" cy="12" r="1.5" fill="#621111"/>
                </svg>
            </div>
            <p>Agregar imagenes de tu producto</p>
            <input type="file" id="input-imagenes" name="imagenes[]" accept="image/*" multiple style="display:none;">
        </div>
        <div id="preview-imagenes" class="preview-imagenes"></div>

        <!-- Botones para crear o borrar el producto -->
        <button type="submit" onclick.location.href="'mostrar-receta.php'">Crear Receta</button>
        <button type="reset" id="cancelar-boton">Borrar</button>
    </form>
</div>