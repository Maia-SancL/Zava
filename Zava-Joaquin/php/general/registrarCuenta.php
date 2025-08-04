<?php

session_start();
include_once 'php/componentes/header.php';
include_once 'php/componentes/navegador.php';
include_once 'conexion.php';

$mensaje = '';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: iniciarSesion.php");
    exit;
}

$id_usuario = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['nombre']) &&
        isset($_POST['descripcion']) &&
        isset($_POST['ingredientes']) &&
        isset($_POST['pasos']) &&
        isset($_POST['tipo_comida'])
    ) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $ingredientes = mysqli_real_escape_string($conexion, $_POST['ingredientes']);
        $pasos = mysqli_real_escape_string($conexion, $_POST['pasos']);
        $tipo_comida = mysqli_real_escape_string($conexion, $_POST['tipo_comida']);

        // Manejo de imágenes
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

        // Convertir las rutas de las imágenes a un formato JSON para almacenarlas en la base de datos
        $imagenes_json = json_encode($imagenes);

        // Insertar receta en la base de datos
        $query = "INSERT INTO recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tipo_comida, imagenes) 
                  VALUES ($id_usuario, '$nombre', '$descripcion', '$ingredientes', '$pasos', '$tipo_comida', '$imagenes_json')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            $mensaje = "Receta creada exitosamente.";
        } else {
            $mensaje = "Error al crear la receta.";
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}
?>

<div class="crear-receta">
    <h1>Crear Receta</h1>
    <?php if ($mensaje): ?>
        <p style="color:red;"><?= $mensaje ?></p>
    <?php endif; ?>
    <form action="crear.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre de la receta:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea><br>

        <label for="ingredientes">Ingredientes (separados por puntos):</label>
        <textarea name="ingredientes" id="ingredientes" placeholder="Ejemplo: Harina. Azúcar. Huevos." required></textarea><br>

        <label for="pasos">Pasos (separados por comas):</label>
        <textarea name="pasos" id="pasos" placeholder="Ejemplo: Mezclar ingredientes, Hornear a 180°C, Dejar enfriar." required></textarea><br>

        <label for="tipo_comida">Categoría:</label>
        <input type="text" name="tipo_comida" id="tipo_comida" required><br>

        <label for="imagen1">Imagen 1:</label>
        <input type="file" name="imagen1" id="imagen1"><br>

        <label for="imagen2">Imagen 2:</label>
        <input type="file" name="imagen2" id="imagen2"><br>

        <label for="imagen3">Imagen 3:</label>
        <input type="file" name="imagen3" id="imagen3"><br>

        <button type="submit">Crear Receta</button>
    </form>
</div>
